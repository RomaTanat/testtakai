"""
Fairytale Generator API
Supports: OpenAI GPT-3.5 | HuggingFace Mistral-7B (free)
"""

from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from pydantic import BaseModel, Field, field_validator
from typing import List, AsyncGenerator
from datetime import datetime
import os
from dotenv import load_dotenv

load_dotenv()

app = FastAPI(title="Fairytale Generator API")
app.add_middleware(CORSMiddleware, allow_origins=["*"], allow_credentials=True, 
                   allow_methods=["*"], allow_headers=["*"])

AI_MODEL = os.getenv("AI_MODEL", "huggingface").lower()
OPENAI_API_KEY = os.getenv("OPENAI_API_KEY", "")

if AI_MODEL == "openai":
    if not OPENAI_API_KEY:
        print("⚠️  WARNING: OPENAI_API_KEY not set")
    try:
        from openai import OpenAI
        openai_client = OpenAI(api_key=OPENAI_API_KEY)
        MODEL_NAME = "gpt-3.5-turbo"
        print(f"✅ Using OpenAI: {MODEL_NAME}")
    except ImportError:
        print("⚠️  OpenAI not installed, falling back to HuggingFace")
        AI_MODEL = "huggingface"

if AI_MODEL == "huggingface":
    from huggingface_hub import InferenceClient
    hf_client = InferenceClient()
    MODEL_NAME = "mistralai/Mistral-7B-Instruct-v0.2"
    print(f"✅ Using HuggingFace: {MODEL_NAME} (FREE)")


class StoryRequest(BaseModel):
    age: int = Field(..., gt=0)
    language: str = Field(...)
    characters: List[str] = Field(..., min_length=1)
    
    @field_validator('language')
    @classmethod
    def validate_language(cls, v):
        if v not in ['ru', 'kk']:
            raise ValueError('Language must be "ru" or "kk"')
        return v
    
    @field_validator('characters')
    @classmethod
    def validate_characters(cls, v):
        if not v or len(v) == 0:
            raise ValueError('At least one character is required')
        return v


def create_prompt(age: int, language: str, characters: List[str]) -> str:
    language_names = {'ru': 'русском', 'kk': 'казахском'}
    characters_str = ', '.join(characters)
    
    return f"""Напиши короткую сказку на {language_names[language]} языке для ребёнка {age} лет.

Персонажи: {characters_str}

Требования:
- Подходит для возраста {age} лет
- Содержит моральный урок
- Интересный сюжет
- Объём 200-300 слов

Формат ответа в Markdown:
- Начни с заголовка сказки
- Используй параграфы
- В конце укажи мораль"""


async def generate_story_stream(request: StoryRequest) -> AsyncGenerator[str, None]:
    try:
        prompt = create_prompt(request.age, request.language, request.characters)
        language_names = {'ru': 'русский', 'kk': 'казахский'}
        
        header = f"""# Сказка для {request.age}-летнего ребёнка

**Язык:** {language_names[request.language]}  
**Персонажи:** {', '.join(request.characters)}  
**Модель:** {MODEL_NAME}

---

"""
        yield header
        
        if AI_MODEL == "openai":
            response = openai_client.chat.completions.create(
                model=MODEL_NAME,
                messages=[
                    {"role": "system", "content": "Ты профессиональный сказочник, создающий добрые и поучительные истории для детей."},
                    {"role": "user", "content": prompt}
                ],
                max_tokens=500,
                temperature=0.8,
                stream=True
            )
            
            for chunk in response:
                if chunk.choices[0].delta.content:
                    yield chunk.choices[0].delta.content
        else:
            stream = hf_client.text_generation(
                prompt,
                model=MODEL_NAME,
                max_new_tokens=500,
                temperature=0.8,
                stream=True
            )
            
            for chunk in stream:
                yield chunk
        
        timestamp = datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
        yield f"\n\n---\n\n*Сказка сгенерирована: {timestamp}*\n"
        
    except Exception as e:
        yield f"\n\n**Ошибка при генерации сказки:** {str(e)}\n"


@app.get("/")
async def root():
    return {
        "name": "Fairytale Generator API",
        "version": "3.0.0",
        "current_model": AI_MODEL,
        "model_name": MODEL_NAME,
        "api_key_required": AI_MODEL == "openai",
        "supports": ["OpenAI (requires API key)", "HuggingFace (FREE)"],
        "endpoints": {
            "generate_story": "/generate_story (POST)",
            "health": "/health (GET)"
        }
    }


@app.post("/generate_story")
async def generate_story(request: StoryRequest):
    try:
        return StreamingResponse(
            generate_story_stream(request),
            media_type="text/markdown",
            headers={"Cache-Control": "no-cache", "X-Accel-Buffering": "no"}
        )
    except ValueError as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error: {str(e)}")


@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "model_type": AI_MODEL,
        "model_name": MODEL_NAME,
        "api_key_required": AI_MODEL == "openai",
        "api_key_configured": bool(OPENAI_API_KEY) if AI_MODEL == "openai" else None
    }
