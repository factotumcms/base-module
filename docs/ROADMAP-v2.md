# Base Module v2.0 - Roadmap

> **Stato:** 📋 Proposta / Pianificazione  
> **Versione Target:** 2.0.0  
> **Versione Attuale:** 1.7.1  
> **Breaking Changes:** ⚠️ Sì - Richiede migrazione dati

## Panoramica

La versione 2.0 del Base Module introduce il supporto **OAuth con Laravel Socialite**:
- 🔐 Autenticazione OAuth multi-provider
- 👥 Login con Google, GitHub, Microsoft, Facebook, Apple
- 🔗 Un utente può collegare multipli account social
- 🎨 Sync automatico profilo da provider social
- 🔒 Password opzionale (solo OAuth o email+password)

---

## Riepilogo Modifiche v2.0

### 🎯 Obiettivi Principali

| Area | Modifiche | Impatto |
|------|-----------|---------|
| **Architettura Auth** | Separazione Account/User + Multi-provider OAuth | 🔴 Breaking |
| **API Standard** | RFC 7807 Problem JSON + Response senza wrapper + Paginazione headers | 🔴 Breaking |
| **API Media** | CRUD completo con endpoint standard REST | 🔴 Breaking |
| **Permessi** | Standardizzazione naming + CRUD completo per tutte le risorse | 🔴 Breaking |
| **Audit Trail** | Tracking metadata su tutte le tabelle | 🟡 Additivo |
| **CASL Integration** | Permission transformer + `/me` endpoint | 🟢 Nuovo |

### 📊 Macro-Aree di Intervento

#### 1. Autenticazione & OAuth Management
- ✅ Nuova tabella `social_accounts` (standard Laravel Socialite)
- ✅ **Sistema recupero password completo:** forgot-password, reset-password, change-password
- ✅ **Logout con invalidazione token**
- ✅ **Email notifications:** password reset, password changed
- ✅ OAuth2 con Google, GitHub, Microsoft, Facebook, Apple
- ✅ Link/Unlink multipli account social per utente
- ✅ Password opzionale (user può registrarsi solo con OAuth)

#### 2. Media Manager
- ✅ CRUD completo: create, read, update, delete
- ✅ Endpoint `/download` separato per file binari
- ✅ Endpoint `/conversions/{preset}` per versioni ridimensionate
- ✅ Nuovi permessi: `update_media`, `delete_media`

#### 3. Sistema Permessi
**Standardizzazione Naming (Breaking Changes):**
- `edit_users` → `update:user`
- `delete_users` → `delete:user`
- `read_settings` → `read:setting`
- `update_settings` → `update:setting`
- `read_permissions` → `read:permission`
- `upload_media` → `create:media` ⚠️
- `read_media` → `read:media` ⚠️

**CRUD Completo:**
- **Media:** `create:media`, `read:media`, `update:media`, `delete:media`
- **Setting:** `create:setting`, `read:setting`, `update:setting`, `delete:setting`
- **Permission:** `create:permission`, `read:permission`, `update:permission`, `delete:permission`

#### 4. Tracking & Audit
Campi aggiunti a **tutte** le tabelle:
- `created_by` - Utente che ha creato il record
- `created_at` - Timestamp di creazione del record
- `updated_by` - Utente che ha modificato il record
- `updated_at` - Timestamp di creazione del record
- `deleted_by` - Utente che ha eliminato il record
- `deleted_at` - Timestamp soft delete

#### 5. Standard API v2.0 (Tutti gli Endpoint)
- ✅ **RFC 7807 Problem JSON** - Formato errori standardizzato per tutti i 4xx/5xx
- ✅ **Response senza wrapper** - Output diretto di entità (oggetto o array)
- ✅ **Liste sempre paginate** - Headers `X-Total-Count`, `X-Page-Number`, `X-Page-Size`, `X-Total-Pages`
- ✅ **Query params paginazione** - `page[number]` (default: 1) e `page[size]` (default: 100, max: 500)
- ✅ **CORS headers** - `Access-Control-Expose-Headers` per lettura headers client-side

#### 6. Frontend Integration
- ✅ CASL Permission Transformer Service
- ✅ `/api/v1/me` endpoint con abilities
- ✅ Response format standardizzato

### 📈 Metriche di Impatto

| Metrica | v1.7 | v2.0 | Δ |
|---------|------|------|---|
| **Tabelle Database** | 10 | 12 | +2 |
| **Permessi Totali** | 12 | 27 | +15 |
| **API Endpoints Auth** | 2 | 10 | +8 |
| **API Endpoints Totali** | ~25 | ~41 | +16 |
| **OAuth Providers** | 0 | 5 | +5 |
| **Email Templates** | 0 | 3 | +3 |
| **Tabelle con Audit** | 2 | 10 | +8 |

---

## 1. Architettura OAuth con Laravel Socialite

### 1.1 Schema Database

**Relazione User → Accounts (1:N):**

```
┌─────────────────────────────────────────────────────────────┐
│                          USERS                               │
├─────────────────────────────────────────────────────────────┤
│ id                  │ bigint (PK)                            │
│ avatar_id           │ bigint (FK -> media, nullable)         │
│ first_name          │ string                                 │
│ last_name           │ string                                 │
│ username            │ string (unique, nullable)              │
│ email               │ string (unique)                        │
│ email_verified_at   │ timestamp (nullable)                   │
│ password            │ string (nullable, hashed)              │
│ is_active           │ boolean (default: true)                │
│ last_login_at       │ timestamp (nullable)                   │
│ remember_token      │ string (nullable)                      │
│ created_at          │ timestamp                              │
│ updated_at          │ timestamp                              │
│ deleted_at          │ timestamp (nullable)                   │
│ is_deleted          │ boolean (default: false)               │
│ created_by          │ bigint (FK -> users, nullable)         │
│ updated_by          │ bigint (FK -> users, nullable)         │
│ deleted_by          │ bigint (FK -> users, nullable)         │
└─────────────────────────────────────────────────────────────┘
                              │
                              │ 1:N
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     SOCIAL_ACCOUNTS                          │
├─────────────────────────────────────────────────────────────┤
│ id                  │ bigint (PK)                            │
│ user_id             │ bigint (FK -> users)                   │
│ provider            │ string (google, github, microsoft...)  │
│ provider_id         │ string                                 │
│ name                │ string (nullable)                      │
│ nickname            │ string (nullable)                      │
│ email               │ string (nullable)                      │
│ avatar              │ string (nullable) - URL avatar         │
│ token               │ text (nullable) - OAuth access token   │
│ refresh_token       │ text (nullable)                        │
│ expires_at          │ timestamp (nullable)                   │
│ created_at          │ timestamp                              │
│ updated_at          │ timestamp                              │
│                                                              │
│ UNIQUE(provider, provider_id)                                │
│ INDEX(user_id)                                               │
└─────────────────────────────────────────────────────────────┘
```

**Note:**
- Un utente può avere **un solo account principale** con email/password
- Un utente può collegare **multipli account social** (Google, GitHub, Microsoft, etc.)
- La tabella `social_accounts` segue lo standard Laravel Socialite
- `password` può essere `NULL` se l'utente si registra solo via OAuth

### 1.2 Provider OAuth Supportati

| Provider | Socialite Driver | Pacchetto |
|----------|------------------|-----------|
| **Google** | `google` | Laravel Socialite (core) |
| **GitHub** | `github` | Laravel Socialite (core) |
| **Microsoft** | `microsoft` | `socialiteproviders/microsoft` |
| **Facebook** | `facebook` | Laravel Socialite (core) |
| **Apple** | `apple` | `socialiteproviders/apple` |

### 1.3 Sistema Recupero Password

**Tabella Password Reset Tokens:**

```
┌─────────────────────────────────────────────────────────────┐
│                  PASSWORD_RESET_TOKENS                       │
├─────────────────────────────────────────────────────────────┤
│ email               │ string (indexed)                       │
│ token               │ string (hashed)                        │
│ created_at          │ timestamp                              │
│                                                              │
│ INDEX(email)                                                 │
└─────────────────────────────────────────────────────────────┘
```

**Configurazione:**

```php
// config/factotum_base.php
'auth' => [
    'password_reset_token_expiration' => env('PASSWORD_RESET_TOKEN_EXPIRATION', 60), // minuti
    'password_reset_throttle' => env('PASSWORD_RESET_THROTTLE', 5), // tentativi per minuto
],
```

**Flow Recupero Password:**

```
1. User → POST /api/v1/forgot-password { email }
   ↓
2. Sistema genera token random (hashed in DB)
   ↓
3. Sistema invia email con link contenente token
   ↓
4. User clicca link → Frontend apre form reset
   ↓
5. User → POST /api/v1/reset-password { email, token, password, password_confirmation }
   ↓
6. Sistema valida token (non scaduto, corretto hash)
   ↓
7. Sistema aggiorna password e invalida token
   ↓
8. Success → User può fare login con nuova password
```

**Notifiche Email:**

| Evento | Template | Descrizione |
|--------|----------|-------------|
| Password Reset Request | `password-reset-request` | Email con link e token per reset |
| Password Reset Success | `password-reset-success` | Conferma cambio password avvenuto |
| Password Changed | `password-changed` | Notifica cambio password da profilo |

---

## 2. Standard API v2.0 - Requisiti Globali

> **📖 Documentazione Completa:** [[API-Standards]](./API-Standards.md)

**Tutti gli endpoint API v2.0 seguono questi standard globali:**

| Standard | Descrizione | Documentazione |
|----------|-------------|----------------|
| **RFC 7807** | Errori 4xx/5xx con formato Problem JSON | [[API-Standards § 2]](./API-Standards.md#2-rfc-7807-problem-json) |
| **Response Direct** | Output diretto entità (no wrapper `data`) | [[API-Standards § 3]](./API-Standards.md#3-response-format) |
| **Pagination Headers** | Liste con `X-Total-Count`, `page[number]`, `page[size]` | [[API-Standards § 4]](./API-Standards.md#4-paginazione) |
| **Filters** | `filter[campo]=valore`, operatori, relazioni, JSON | [[API-Standards § 5]](./API-Standards.md#5-filtri-e-ricerca) |
| **Sorting** | `sort=campo`, `sort=-campo` (multi-sort) | [[API-Standards § 6]](./API-Standards.md#6-ordinamento-sort) |
| **Sparse Fields** | `fields[resource]=campo1,campo2` | [[API-Standards § 7]](./API-Standards.md#7-selezione-campi-sparse-fieldsets) |
| **Includes** | `include=relazione1,relazione2` | [[API-Standards § 8]](./API-Standards.md#8-inclusione-relazioni) |
| **CRUD Naming** | Risorse plurali, kebab-case, RESTful | [[API-Standards § 9]](./API-Standards.md#9-crud-standard) |
| **Permissions** | Pattern `action:subject` (es. `create:user`) | [[API-Standards § 10]](./API-Standards.md#10-permessi--autorizzazione) |

---

### 2.1 Tracking Metadata (Audit Trail)

**Campi aggiunti a tutte le tabelle:**

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `created_by` | bigint (FK → users, nullable) | ID utente che ha creato il record |
| `created_at` | timestamp (nullable) | Timestamp di creazione record |
| `updated_by` | bigint (FK → users, nullable) | ID utente che ha modificato il record |
| `updated_at` | timestamp (nullable) | Timestamp di creazione record |
| `deleted_by` | bigint (FK → users, nullable) | ID utente che ha eliminato il record (soft delete) |
| `deleted_at` | timestamp (nullable) | Data eliminazione (soft delete) |
| `is_deleted` | boolean (default: false) | Flag booleano per indicare se il record è eliminato |

**Tabelle interessate:**
- ✅ `accounts`
- ✅ `users`
- ✅ `account_providers`
- ✅ `password_histories` (già esistente)
- ✅ `roles` (Spatie Permission)
- ✅ `permissions` (Spatie Permission)
- ✅ `settings`
- ✅ `media`
- ✅ `notifications`

**Implementazione:**

```php
// Trait per auto-populate tracking fields
trait HasTrackingMetadata
{
    protected static function bootHasTrackingMetadata()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
        
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
        
        static::deleting(function ($model) {
            if (auth()->check() && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $model->deleted_by = auth()->id();
                $model->is_deleted = true;
                $model->save();
            }
        });
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
```

**Uso nel Model:**

```php
class Account extends Model
{
    use SoftDeletes, HasTrackingMetadata;
    
    protected $fillable = ['email', 'is_active'];
}
```

---

## 3. Sistema OAuth con Laravel Socialite

**Features:**
- ✨ Login/Register con Google, GitHub, Microsoft, Facebook, Apple
- ✨ Un utente può collegare multipli account social
- ✨ Link/Unlink account social
- ✨ Social profile sync automatico (nome, email, avatar)
- ✨ Password opzionale (user può registrarsi solo con OAuth)
- ✨ Auto-merge account con stessa email (configurabile)

**Flusso OAuth Standard:**

```
1. Frontend richiede URL di autorizzazione
   → GET /api/v1/oauth/{provider}/redirect
   
2. Backend genera URL OAuth e lo restituisce
   ← { "url": "https://accounts.google.com/o/oauth2/v2/auth?..." }
   
3. User viene rediretto al provider OAuth (Google, GitHub, etc.)
   
4. User autorizza l'app
   
5. Provider redirige a callback: /callback?code=xxx&state=yyy
   
6. Frontend invia code al backend
   → POST /api/v1/oauth/{provider}/callback { "code": "xxx" }
   
7. Backend scambia code con access token tramite Socialite
   
8. Backend crea/aggiorna User e SocialAccount
   
9. Backend genera token Sanctum
   ← { "token": "...", "user": {...}, "social_account": {...} }
```

### 3.1 API Endpoints Autenticazione v2.0

#### Autenticazione Base (Public)
```
POST   /api/v1/login              - Login con email/username + password
POST   /api/v1/register           - Registrazione nuovo account
POST   /api/v1/logout             - Logout (invalida token Sanctum)
POST   /api/v1/forgot-password    - Richiesta reset password (invia email con token)
POST   /api/v1/reset-password     - Reset password con token ricevuto via email
PATCH  /api/v1/change-password    - Cambio password per utente autenticato
```

**Request/Response Examples:**

**Logout:**
```json
// Request
POST /api/v1/logout
Authorization: Bearer {token}

// Response
HTTP/1.1 204 No Content
```

**Forgot Password:**
```json
// Request
POST /api/v1/forgot-password
{
  "email": "user@example.com"
}

// Response
HTTP/1.1 204 No Content
```

**Reset Password:**
```json
// Request
POST /api/v1/reset-password
{
  "email": "user@example.com",
  "token": "reset_token_from_email",
  "password": "NewPassword123!",
  "password_confirmation": "NewPassword123!"
}

// Response
HTTP/1.1 204 No Content
```

**Change Password:**
```json
// Request
PATCH /api/v1/change-password
Authorization: Bearer {token}
{
  "current_password": "OldPassword123!",
  "password": "NewPassword123!",
  "password_confirmation": "NewPassword123!"
}

// Response
HTTP/1.1 204 No Content
```

#### Autenticazione OAuth (Public)
```
GET    /api/v1/oauth/{provider}/redirect   - Ottieni URL OAuth per autorizzazione
POST   /api/v1/oauth/{provider}/callback   - Callback OAuth (scambia code con token)
```

#### Gestione Social Accounts (Protected)
```
GET    /api/v1/me/social-accounts           - Lista account social collegati
POST   /api/v1/me/social-accounts/{provider}/link   - Collega nuovo account social
DELETE /api/v1/me/social-accounts/{provider}         - Scollega account social
```

**Request/Response Examples OAuth:**

**1. Redirect - Ottieni URL Autorizzazione:**
```json
// Request
GET /api/v1/oauth/google/redirect

// Response
{
  "url": "https://accounts.google.com/o/oauth2/v2/auth?client_id=...&redirect_uri=...&response_type=code&scope=email%20profile"
}
```

**2. Callback - Login/Register con OAuth:**
```json
// Request
POST /api/v1/oauth/google/callback
{
  "code": "4/0AX4XfWh...",
  "state": "random_state_string"
}

// Response (Nuovo utente creato)
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "avatar_url": "https://lh3.googleusercontent.com/a/...",
    "created_at": "2025-12-11T10:00:00Z"
  },
  "social_account": {
    "id": 1,
    "provider": "google",
    "provider_id": "1234567890",
    "email": "john@example.com",
    "created_at": "2025-12-11T10:00:00Z"
  }
}

// Response (Utente esistente - link account)
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
  "user": { ... },
  "social_account": {
    "id": 2,
    "provider": "google",
    "provider_id": "1234567890",
    "email": "john@example.com",
    "created_at": "2025-12-11T10:00:00Z"
  }
}
```

**3. Lista Social Accounts:**
```http
// Request
GET /api/v1/me/social-accounts?page[number]=1&page[size]=100
Authorization: Bearer {token}

// Response
HTTP/1.1 200 OK
Content-Type: application/json
X-Total-Count: 2
X-Page-Number: 1
X-Page-Size: 100
X-Total-Pages: 1
Access-Control-Expose-Headers: X-Total-Count, X-Page-Number, X-Page-Size, X-Total-Pages

[
  {
    "id": 1,
    "provider": "google",
    "provider_id": "1234567890",
    "email": "john@example.com",
    "name": "John Doe",
    "avatar": "https://lh3.googleusercontent.com/a/...",
    "created_at": "2025-12-11T10:00:00Z"
  },
  {
    "id": 2,
    "provider": "github",
    "provider_id": "9876543",
    "email": "john@example.com",
    "nickname": "johndoe",
    "avatar": "https://avatars.githubusercontent.com/u/...",
    "created_at": "2025-12-11T11:00:00Z"
  }
]
```

**4. Unlink Social Account:**
```json
// Request
DELETE /api/v1/me/social-accounts/google
Authorization: Bearer {token}

// Response
HTTP/1.1 204 No Content
```

### 3.2 Relazioni Eloquent

**User Model (v2.0):**
- `socialAccounts()` → HasMany SocialAccount
- `avatar()` → BelongsTo Media
- `roles()` → MorphToMany (Spatie Permission)
- `permissions()` → MorphToMany (Spatie Permission)
- `settings()` → BelongsToMany Settings (pivot con value override)
- `notifications()` → HasMany Notification
- `passwordHistories()` → HasMany PasswordHistory

**SocialAccount Model:**
- `user()` → BelongsTo User

**Metodi Helper User Model:**
```php
// Verifica se user ha account social per provider
public function hasSocialAccount(string $provider): bool;

// Ottieni account social per provider
public function getSocialAccount(string $provider): ?SocialAccount;

// Verifica se user ha password impostata
public function hasPassword(): bool;
```

---

## 4. API Media - Refactoring CRUD

### 4.1 Problemi API v1.7

Le API media attuali non rispettano lo standard CRUD:

**v1.7 (Obsoleto):**
```
GET    /api/v1/media                 - Lista media
GET    /api/v1/media/{media}         - Visualizza file (download diretto)
POST   /api/v1/media                 - Crea media (chiamato "upload" in v1.7)
```

**Limitazioni:**
- ❌ Nessun endpoint per ottenere dettagli/metadati del media
- ❌ `GET /api/v1/media/{media}` restituisce il file binario invece dei dati JSON
- ❌ Impossibile aggiornare il nome del media
- ❌ Nessuna funzionalità di eliminazione
- ❌ Non segue pattern REST standard

### 4.2 Nuove API v2.0

**v2.0 (Standard CRUD Completo):**

| Azione | Endpoint | Metodo | Permesso | Descrizione |
|--------|----------|--------|----------|-------------|
| Lista | `/api/v1/media` | GET | `read:media` | Lista paginata con filtri |
| Dettaglio | `/api/v1/media/{media}` | GET | `read:media` | Metadati JSON del media |
| Create | `/api/v1/media` | POST | `create:media` | Crea nuovo media (upload file) |
| Update | `/api/v1/media/{media}` | PUT/PATCH | `update:media` | Modifica nome media |
| Elimina | `/api/v1/media/{media}` | DELETE | `delete:media` | Soft delete media |
| Download | `/api/v1/media/{media}/download` | GET | `read:media` | Download file binario |
| Conversione | `/api/v1/media/{media}/conversions/{preset}` | GET | `read:media` | Download conversione specifica |

### 4.3 Response Format

**GET `/api/v1/media/{media}` - Dettaglio Media:**

```json
{
  "id": 1,
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "name": "Avatar Utente",
  "file_name": "avatar.jpg",
  "mime_type": "image/jpeg",
  "media_type": "image",
  "disk": "public",
  "path": "media/2025/12",
  "size": 204800,
  "url": "https://example.com/storage/media/2025/12/avatar.jpg",
  "presets": ["user_avatar_preset"],
  "conversions": {
    "user_avatar_preset": {
      "url": "https://example.com/storage/media/conversions/avatar_avatar.jpg",
      "size": 15360,
      "mime_type": "image/jpeg"
    }
  },
  "custom_properties": {
    "alt": "Avatar dell'utente",
    "caption": "Foto profilo"
  },
  "created_at": "2025-12-11T10:00:00Z",
  "updated_at": "2025-12-11T10:00:00Z"
}
```

**PUT `/api/v1/media/{media}` - Update Media:**

Request (full object required):
```json
{
  "name": "Nuovo Nome Media",
  "custom_properties": {
    "alt": "Avatar dell'utente",
    "caption": "Foto profilo"
  }
}
```

Response:
```json
{
  "id": 1,
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "name": "Nuovo Nome Media",
  "file_name": "avatar.jpg",
  "mime_type": "image/jpeg",
  "media_type": "image",
  "disk": "public",
  "path": "media/2025/12",
  "size": 204800,
  "url": "https://example.com/storage/media/2025/12/avatar.jpg",
  "custom_properties": {
    "alt": "Avatar dell'utente",
    "caption": "Foto profilo"
  },
  "created_at": "2025-12-11T10:00:00Z",
  "updated_at": "2025-12-11T11:00:00Z"
}
```

**DELETE `/api/v1/media/{media}` - Soft Delete:**

Response:
```
HTTP/1.1 204 No Content
```

### 4.4 Nuovi Permessi Media

| Permesso | Valore | Descrizione |
|----------|--------|-------------|
| `CREATE_MEDIA` | `create:media` | Creare nuovo media (upload file) |
| `READ_MEDIA` | `read:media` | Visualizzare media e scaricare |
| `UPDATE_MEDIA` | `update:media` | Modificare nome/proprietà |
| `DELETE_MEDIA` | `delete:media` | Eliminare media |

> **Note v1.7 → v2.0:**
> - ⚠️ **Breaking:** `upload_media` → `create:media` (cambiato nome e delimiter)
> - ⚠️ **Breaking:** `read_media` → `read:media` (aggiunto delimiter)
> - **NUOVO:** `update:media` - Modifica nome/proprietà
> - **NUOVO:** `delete:media` - Eliminazione

### 4.5 Filtri, Ricerca e Paginazione

**Request:**
```
GET /api/v1/media?filter[media_type]=image&filter[mime_type]=image/jpeg&sort=-created_at&page[number]=1&page[size]=100
```

**Parametri Paginazione:**
- `page[number]` - Numero pagina (default: 1)
- `page[size]` - Dimensione pagina (default: 100, max: 500)

**Filtri supportati:**
- `media_type` - Tipo media (image, video, document, etc.)
- `mime_type` - MIME type specifico
- `disk` - Disco storage
- `name` - Ricerca per nome (LIKE)
- `created_at` - Range date

**Response con Headers di Paginazione:**

```http
HTTP/1.1 200 OK
Content-Type: application/json
X-Total-Count: 250
X-Page-Number: 1
X-Page-Size: 100
X-Total-Pages: 3
Access-Control-Expose-Headers: X-Total-Count, X-Page-Number, X-Page-Size, X-Total-Pages

[
  {
    "id": 1,
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "name": "Avatar Utente",
    "file_name": "avatar.jpg",
    "mime_type": "image/jpeg",
    "media_type": "image",
    "url": "https://example.com/storage/media/2025/12/avatar.jpg",
    "size": 204800,
    "created_at": "2025-12-11T10:00:00Z"
  },
  ...
]
```

**Headers di Paginazione:**
- `X-Total-Count` - Numero totale di record
- `X-Page-Number` - Numero pagina corrente
- `X-Page-Size` - Dimensione pagina corrente
- `X-Total-Pages` - Numero totale di pagine
- `Access-Control-Expose-Headers` - Abilita lettura headers lato client (CORS)

### 4.6 Tracking Metadata

Estensione tabella `media` con campi audit:

```
┌─────────────────────────────────────────────────────────────┐
│                         MEDIA                                │
├─────────────────────────────────────────────────────────────┤
│ id                  │ bigint (PK)                            │
│ uuid                │ uuid (nullable, unique)                │
│ name                │ string                                 │
│ file_name           │ string                                 │
│ mime_type           │ string                                 │
│ media_type          │ string (indexed)                       │
│ presets             │ text (array serialized, nullable)      │
│ disk                │ string                                 │
│ path                │ string                                 │
│ size                │ bigint (bytes)                         │
│ custom_properties   │ longtext (JSON, nullable)              │
│ conversions         │ longtext (JSON, nullable)              │
│ created_at          │ timestamp                              │
│ updated_at          │ timestamp                              │
│ deleted_at          │ timestamp (nullable)                   │
│ is_deleted          │ boolean (default: false)               │
│ created_by          │ bigint (FK -> users, nullable)         │
│ updated_by          │ bigint (FK -> users, nullable)         │
│ deleted_by          │ bigint (FK -> users, nullable)         │
│                                                              │
│ UNIQUE(file_name, disk, path)                                │
└─────────────────────────────────────────────────────────────┘
```

**Campi aggiunti:**
- `is_deleted` - Flag soft delete
- `created_by` - Utente che ha caricato
- `updated_by` - Utente che ha modificato
- `deleted_by` - Utente che ha eliminato

### 4.7 Servizio Media Update

```php
namespace Wave8\Factotum\Base\Services;

class MediaService implements MediaServiceInterface
{
    /**
     * Aggiorna il nome del media
     */
    public function update(Media $media, array $data): Media
    {
        $media->update([
            'name' => $data['name'] ?? $media->name,
            'custom_properties' => array_merge(
                $media->custom_properties ?? [],
                $data['custom_properties'] ?? []
            ),
        ]);
        
        return $media->fresh();
    }
    
    /**
     * Soft delete media
     */
    public function delete(Media $media): bool
    {
        // Elimina fisicamente le conversioni
        $this->deleteConversions($media);
        
        // Soft delete record database
        return $media->delete();
    }
    
    /**
     * Download file originale
     */
    public function download(Media $media): StreamedResponse
    {
        $disk = Storage::disk($media->disk);
        $fullPath = $media->path . '/' . $media->file_name;
        
        if (!$disk->exists($fullPath)) {
            throw new MediaNotFoundException();
        }
        
        return $disk->download($fullPath, $media->name);
    }
}
```

---

## 5. Configurazione OAuth

```php
// config/factotum_base.php
'permissions' => [
    // Delimiter tra action e subject nei permessi (es: 'create:user', 'read:media')
    'delimiter' => env('PERMISSION_DELIMITER', ':'),
],

'oauth' => [
    'providers' => [
        'google' => [
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect' => env('GOOGLE_REDIRECT_URI'),
            'scopes' => ['email', 'profile'],
        ],
        'github' => [
            'client_id' => env('GITHUB_CLIENT_ID'),
            'client_secret' => env('GITHUB_CLIENT_SECRET'),
            'redirect' => env('GITHUB_REDIRECT_URI'),
            'scopes' => ['user:email'],
        ],
        'microsoft' => [
            'client_id' => env('MICROSOFT_CLIENT_ID'),
            'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
            'redirect' => env('MICROSOFT_REDIRECT_URI'),
            'tenant' => env('MICROSOFT_TENANT', 'common'),
            'scopes' => ['openid', 'profile', 'email'],
        ],
    ],
    
    // Auto-link account con stessa email
    'auto_link_by_email' => env('OAUTH_AUTO_LINK', true),
    
    // Crea account se non esiste
    'auto_create_account' => env('OAUTH_AUTO_CREATE', true),
    
    // Sync dati profilo da provider
    'sync_profile' => [
        'name' => true,
        'avatar' => true,
        'email' => false, // Non sovrascrivere email
    ],
],
```

---

## 6. Standardizzazione Completa Permessi

### 6.1 Problemi Naming v1.7

**Incongruenze Identificate:**

| Modulo | v1. (Inconsistente) | v2.0 (Corretto) | Problema |
|--------|---------------------|-----------------|----------|
| **User** | `edit_users` | `update:user` | Action "edit" invece di "update" + delimiter + plurale |
| **User** | `delete_users` | `delete:user` | Subject plurale + delimiter |
| **Setting** | `read_settings` | `read:setting` | Subject plurale + delimiter |
| **Setting** | `update_settings` | `update:setting` | Subject plurale + delimiter |
| **Permission** | `read_permissions` | `read:permission` | Subject plurale + delimiter |
| **Media** | `upload_media` | `create:media` | Action "upload" non standard CRUD + delimiter |

**Altri Permessi Mancanti:**
- Media: `upload_media` (non standard CRUD) e `read_media` (mancano `update_media`, `delete_media`)
- Setting: mancano `create_setting`, `delete_setting`
- Permission: mancano `create_permission`, `update_permission`, `delete_permission`

### 6.2 Standard v2.0

**Regole di Naming v2.0:**
1. ✅ Pattern: `{action}:{subject}` (delimiter configurabile, default `:`)
2. ✅ Action CRUD standard: `create`, `read`, `update`, `delete`
3. ✅ Subject sempre al **SINGOLARE** (user, role, media, permission, setting)
4. ✅ Lowercase con delimiter (es: `create:user`, `update:media`)
5. ✅ Ogni risorsa deve avere CRUD completo (dove applicabile)
6. ✅ Delimiter configurabile in `config/factotum_base.php` → `permissions.delimiter`

### 6.3 Permessi Completi v2.0

#### UserPermission
```php
enum UserPermission: string
{
    case CREATE_USER = 'create:user';
    case READ_USER = 'read:user';
    case UPDATE_USER = 'update:user';      // v1.7: edit_users ❌
    case DELETE_USER = 'delete:user';      // v1.7: delete_users ❌
}
```

#### MediaPermission
```php
enum MediaPermission: string
{
    case CREATE_MEDIA = 'create:media';
    case READ_MEDIA = 'read:media';
    case UPDATE_MEDIA = 'update:media';    // ⭐ NUOVO
    case DELETE_MEDIA = 'delete:media';    // ⭐ NUOVO
}
```

#### RolePermission
```php
enum RolePermission: string
{
    case CREATE_ROLE = 'create:role';
    case READ_ROLE = 'read:role';
    case UPDATE_ROLE = 'update:role';
    case DELETE_ROLE = 'delete:role';
}
```

#### SettingPermission
```php
enum SettingPermission: string
{
    case CREATE_SETTING = 'create:setting'; // ⭐ NUOVO
    case READ_SETTING = 'read:setting';     // v1.7: read_settings ❌
    case UPDATE_SETTING = 'update:setting'; // v1.7: update_settings ❌
    case DELETE_SETTING = 'delete:setting'; // ⭐ NUOVO
}
```

#### PermissionPermission
```php
enum PermissionPermission: string
{
    case CREATE_PERMISSION = 'create:permission'; // ⭐ NUOVO
    case READ_PERMISSION = 'read:permission';     // v1.7: read_permissions ❌
    case UPDATE_PERMISSION = 'update:permission'; // ⭐ NUOVO
    case DELETE_PERMISSION = 'delete:permission'; // ⭐ NUOVO
}
```

### 6.4 Migration Permessi Database

```php
// Migration: standardize_all_permissions_naming
public function up()
{
    $delimiter = config('factotum_base.permissions.delimiter', ':');
    
    // 1. User Permissions
    DB::table('permissions')->where('name', 'edit_users')->update(['name' => "update{$delimiter}user"]);
    DB::table('permissions')->where('name', 'delete_users')->update(['name' => "delete{$delimiter}user"]);
    DB::table('permissions')->where('name', 'create_user')->update(['name' => "create{$delimiter}user"]);
    DB::table('permissions')->where('name', 'read_user')->update(['name' => "read{$delimiter}user"]);
    
    // 2. Role Permissions
    DB::table('permissions')->where('name', 'create_role')->update(['name' => "create{$delimiter}role"]);
    DB::table('permissions')->where('name', 'read_role')->update(['name' => "read{$delimiter}role"]);
    DB::table('permissions')->where('name', 'update_role')->update(['name' => "update{$delimiter}role"]);
    DB::table('permissions')->where('name', 'delete_role')->update(['name' => "delete{$delimiter}role"]);
    
    // 3. Setting Permissions
    DB::table('permissions')->where('name', 'read_settings')->update(['name' => "read{$delimiter}setting"]);
    DB::table('permissions')->where('name', 'update_settings')->update(['name' => "update{$delimiter}setting"]);
    
    // 4. Permission Permissions
    DB::table('permissions')->where('name', 'read_permissions')->update(['name' => "read{$delimiter}permission"]);
    
    // 5. Media Permissions
    DB::table('permissions')->where('name', 'read_media')->update(['name' => "read{$delimiter}media"]);
    
    // 6. Notification Permissions
    DB::table('permissions')->where('name', 'read_notification')->update(['name' => "read{$delimiter}notification"]);
    DB::table('permissions')->where('name', 'update_notification')->update(['name' => "update{$delimiter}notification"]);
    
    // 7. Aggiungi nuovi permessi Media (CRUD standard)
    DB::table('permissions')->insertOrIgnore([
        ['name' => "create{$delimiter}media", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ['name' => "update{$delimiter}media", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ['name' => "delete{$delimiter}media", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ]);
    
    // 8. Aggiungi nuovi permessi Setting
    DB::table('permissions')->insertOrIgnore([
        ['name' => "create{$delimiter}setting", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ['name' => "delete{$delimiter}setting", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ]);
    
    // 9. Aggiungi nuovi permessi Permission
    DB::table('permissions')->insertOrIgnore([
        ['name' => "create{$delimiter}permission", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ['name' => "update{$delimiter}permission", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ['name' => "delete{$delimiter}permission", 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
    ]);
    
    // 10. Assegna tutti i nuovi permessi al ruolo admin
    $adminRole = DB::table('roles')->where('name', 'admin')->first();
    if ($adminRole) {
        $newPermissions = DB::table('permissions')
            ->where('name', 'like', "%{$delimiter}%")
            ->pluck('id');
        
        foreach ($newPermissions as $permissionId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $adminRole->id,
            ]);
        }
    }
}

public function down()
{
    $delimiter = config('factotum_base.permissions.delimiter', ':');
    
    // Rollback - restore old naming
    DB::table('permissions')->where('name', "update{$delimiter}user")->update(['name' => 'edit_users']);
    DB::table('permissions')->where('name', "delete{$delimiter}user")->update(['name' => 'delete_users']);
    DB::table('permissions')->where('name', "read{$delimiter}setting")->update(['name' => 'read_settings']);
    DB::table('permissions')->where('name', "update{$delimiter}setting")->update(['name' => 'update_settings']);
    DB::table('permissions')->where('name', "read{$delimiter}permission")->update(['name' => 'read_permissions']);
    
    // Elimina nuovi permessi
    DB::table('permissions')
        ->where('name', 'like', "%{$delimiter}%")
        ->whereIn('name', [
            "create{$delimiter}media", "update{$delimiter}media", "delete{$delimiter}media",
            "create{$delimiter}setting", "delete{$delimiter}setting",
            "create{$delimiter}permission", "update{$delimiter}permission", "delete{$delimiter}permission"
        ])
        ->delete();
}
```

### 6.5 Matrice Permessi Completa

| Risorsa | Create | Read | Update | Delete | Note |
|---------|--------|------|--------|--------|------|
| **User** | ✅ `create:user` | ✅ `read:user` | ✅ `update:user` | ✅ `delete:user` | |
| **Role** | ✅ `create:role` | ✅ `read:role` | ✅ `update:role` | ✅ `delete:role` | |
| **Permission** | ⭐ `create:permission` | ✅ `read:permission` | ⭐ `update:permission` | ⭐ `delete:permission` | v1.7: solo read |
| **Setting** | ⭐ `create:setting` | ✅ `read:setting` | ✅ `update:setting` | ⭐ `delete:setting` | v1.7: solo read/update |
| **Media** | ✅ `create:media` | ✅ `read:media` | ⭐ `update:media` | ⭐ `delete:media` | v1.7: upload_media (non-CRUD) |
| **Notification** | - | ✅ `read:notification` | ✅ `update:notification` | - | Solo lettura/mark as read |

⭐ = Nuovo in v2.0

**Note Delimiter:**
- v2.0 usa `:` come delimiter di default (configurabile in `config/factotum_base.php`)
- v1.7 usava `_` (underscore) inconsistente con plurali

---

## 7. Configurazione Laravel Socialite

```php
// config/services.php
return [
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL').'/api/v1/oauth/google/callback'),
    ],
    
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI', env('APP_URL').'/api/v1/oauth/github/callback'),
    ],
    
    'microsoft' => [
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'redirect' => env('MICROSOFT_REDIRECT_URI', env('APP_URL').'/api/v1/oauth/microsoft/callback'),
        'tenant' => env('MICROSOFT_TENANT', 'common'),
    ],
    
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', env('APP_URL').'/api/v1/oauth/facebook/callback'),
    ],
];

// config/factotum_base.php
'oauth' => [
    'enabled_providers' => ['google', 'github', 'microsoft', 'facebook', 'apple'],
    
    // Auto-merge account con stessa email verificata
    'auto_link_by_email' => env('OAUTH_AUTO_LINK', true),
    
    // Permetti registrazione solo via OAuth (disabilita email/password)
    'oauth_only' => env('OAUTH_ONLY', false),
    
    // Sync dati profilo da provider social
    'sync_profile' => [
        'name' => true,
        'email' => false, // Non sovrascrivere email principale
        'avatar' => true,
    ],
],
```

---

## 8. Dipendenze Richieste

| Pacchetto | Versione | Utilizzo |
|-----------|----------|----------|
| `laravel/sanctum` | ^4.0 | API token authentication |
| `laravel/socialite` | ^5.0 | OAuth provider integration (Google, GitHub, Facebook) |
| `socialiteproviders/manager` | ^4.0 | Provider aggiuntivi |
| `socialiteproviders/microsoft` | ^4.0 | Microsoft Azure AD / Office 365 |
| `socialiteproviders/apple` | ^5.0 | Sign in with Apple |

---

## 9. Casi d'Uso

### Scenario 1: Nuovo utente via Google (Auto-register)
```
1. User clicca "Login with Google"
2. GET /api/v1/oauth/google/redirect → Ottiene URL
3. User autorizza su Google
4. POST /api/v1/oauth/google/callback { code }
5. Sistema verifica se esiste User con email Google
6. Non esiste → Crea User + SocialAccount(google)
7. Genera token Sanctum e autentica
```

### Scenario 2: Utente esistente aggiunge Google (Link)
```
1. User loggato (email/password)
2. Va in Profile → Link Social Accounts → Google
3. GET /api/v1/oauth/google/redirect
4. User autorizza su Google
5. POST /api/v1/oauth/google/callback { code }
6. Sistema verifica che user è autenticato
7. Crea SocialAccount(google) per user esistente
8. User ora può login con email/password O Google
```

### Scenario 3: Auto-merge con stessa email
```
1. User registrato con email@example.com + password
2. User prova login con Google (stessa email)
3. Sistema trova User esistente con stessa email
4. Se email_verified_at != null → Auto-link Google account
5. Crea SocialAccount(google) per user esistente
6. User loggato con Google
```

### Scenario 4: Unlink account social
```
1. User ha password + google + github
2. Vuole rimuovere Google
3. DELETE /api/v1/me/social-accounts/google
4. Sistema verifica che esista password O altro social account
5. Elimina SocialAccount(google)
6. User può ancora login con password o GitHub
```

### Scenario 5: OAuth-only (nessuna password)
```
1. User registrato solo con Google (no password)
2. User ha solo SocialAccount(google)
3. Se prova unlink Google → Error (deve avere almeno un metodo login)
4. Deve prima impostare password via "Set Password"
5. Poi può unlink Google
```

---

## 10. Migrazione v1.7 → v2.0

### 10.1 Strategia di Migrazione

```php
// Migration: add_social_accounts_table
public function up()
{
    // 1. Crea tabella social_accounts (standard Laravel Socialite)
    Schema::create('social_accounts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('provider'); // google, github, microsoft, facebook, apple
        $table->string('provider_id');
        $table->string('name')->nullable();
        $table->string('nickname')->nullable();
        $table->string('email')->nullable();
        $table->string('avatar')->nullable();
        $table->text('token')->nullable();
        $table->text('refresh_token')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
        
        $table->unique(['provider', 'provider_id']);
        $table->index('user_id');
    });
    
    // 2. Aggiungi tracking metadata alla tabella users
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_deleted')->default(false)->after('deleted_at');
        $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->nullOnDelete();
        $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
        $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
    });
    
    // 3. Rendi password nullable (per utenti OAuth-only)
    Schema::table('users', function (Blueprint $table) {
        $table->string('password')->nullable()->change();
    });
}

public function down()
{
    Schema::dropIfExists('social_accounts');
    
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['is_deleted', 'created_by', 'updated_by', 'deleted_by']);
        $table->string('password')->nullable(false)->change();
    });
}
```

### 10.2 Standardizzazione Permessi Database

> **⚠️ IMPORTANTE:** Questa migration deve essere eseguita **PRIMA** della migrazione dello schema Account/User per evitare conflitti con il codice esistente che potrebbe fare riferimento ai vecchi nomi dei permessi.

La migration completa per la standardizzazione dei permessi è già documentata nella **Sezione 6.4** di questo roadmap.

**Riepilogo Modifiche:**
- ✅ Rinomina permessi inconsistenti (User, Setting, Permission)
- ✅ Aggiunta permessi CRUD mancanti (Media, Setting, Permission)
- ✅ Assegnazione automatica nuovi permessi al ruolo `admin`
- ✅ Mantenimento compatibilità `upload_media` (deprecato ma funzionante)

**Ordine Esecuzione Migrations:**
1. `standardize_all_permissions_naming` (questa)
2. `split_users_to_accounts_and_users`
3. `add_tracking_metadata_to_tables`

### 10.3 Breaking Changes

⚠️ **Modifiche Non Retrocompatibili:**

> **IMPORTANTE:** Questa è una **major release** - non è garantita alcuna retrocompatibilità con v1.7.
> È necessaria una migrazione completa del database e dell'applicazione.

1. **User `password` nullable** - Password diventa nullable per supportare utenti OAuth-only
2. **Nuova tabella `social_accounts`** - Gestione account social separata
3. **API Login OAuth** - Nuovi endpoint `/oauth/{provider}/redirect` e `/oauth/{provider}/callback`
4. **Nomi Permessi Standardizzati** - Tutti i permessi usano delimiter `:` e subject singolare:
    - `edit_users` → `update:user`
    - `delete_users` → `delete:user`
    - `read_settings` → `read:setting`
    - `update_settings` → `update:setting`
    - `read_permissions` → `read:permission`
    - `upload_media` → `create:media` ⚠️ **NO ALIAS** - Richiede aggiornamento codice
    - `read_media` → `read:media`
5. **API Media** - `GET /api/v1/media/{media}` ora restituisce JSON invece del file binario
    - **v1.7:** Restituisce lo stream del file direttamente
    - **v2.0:** Restituisce metadati JSON; usare `/api/v1/media/{media}/download` per il file
    - Impatto: Frontend che usa `GET /media/{id}` per visualizzare immagini deve usare nuovo endpoint `/download`
6. **Password Reset** - Richiede email (non più username)
7. **Autenticazione** - Login può essere fatto con email/password O OAuth (Socialite)
8. **Response Format** - POST/PUT restituiscono oggetto completo, DELETE restituisce `204 No Content`
9. **Error Format** - Tutti gli errori seguono RFC 7807 Problem JSON

---

## 11. CASL Integration (Frontend)

### 11.1 API Endpoint `/api/v1/me`

**Response Format:**

```json
{
  "user": {
    "id": 1,
    "account_id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "username": "johndoe",
    "avatar_id": 5,
    "avatar_url": "https://example.com/media/avatar.jpg",
    "locale": "it",
    "timezone": "Europe/Rome",
    "created_at": "2025-01-01T00:00:00Z"
  },
  "account": {
    "id": 1,
    "email": "john@example.com",
    "email_verified_at": "2025-01-01T00:00:00Z",
    "is_active": true,
    "last_login_at": "2025-12-11T10:00:00Z"
  },
  "roles": [
    {
      "id": 1,
      "name": "admin",
      "guard_name": "web"
    }
  ],
  "abilities": [
    { "action": "create", "subject": "User" },
    { "action": "read", "subject": "User" },
    { "action": "update", "subject": "User" },
    { "action": "delete", "subject": "User" },
    { "action": "manage", "subject": "all" }
  ]
}
```

### 11.2 Permission Transformer Service

```php
namespace Wave8\Factotum\Base\Services;

class PermissionTransformerService
{
    /**
     * Mappa i permessi Spatie in formato CASL
     */
    public function transformToAbilities(Collection $permissions): array
    {
        return $permissions->map(function ($permission) {
            return $this->parsePermission($permission->name);
        })->filter()->values()->toArray();
    }
    
    /**
     * Parse permission string: {action}:{subject}
     * Examples:
     * - create:user → { action: 'create', subject: 'User' }
     * - update:user → { action: 'update', subject: 'User' }
     * - delete:user → { action: 'delete', subject: 'User' }
     * - manage:all → { action: 'manage', subject: 'all' }
     *
     * Note: Delimiter configurabile in config/factotum_base.php
     * Soggetti già al singolare (user, role, media) → PascalCase (User, Role, Media)
     */
    private function parsePermission(string $permission): ?array
    {
        $delimiter = config('factotum_base.permissions.delimiter', ':');
        
        // Special case: manage:all (admin super permission)
        if ($permission === "manage{$delimiter}all") {
            return ['action' => 'manage', 'subject' => 'all'];
        }
        
        // Split by delimiter: action:subject
        $parts = explode($delimiter, $permission);
        
        if (count($parts) !== 2) {
            return null;
        }
        
        [$action, $subject] = $parts;
        
        // Normalize subject to PascalCase: user -> User
        // Note: soggetti già al singolare, no bisogno di Str::singular()
        $subject = Str::studly($subject);
        
        return [
            'action' => $action,
            'subject' => $subject,
        ];
    }
}
```

### 11.3 Auth Controller

```php
namespace Wave8\Factotum\Base\Http\Controllers\Api;

class AuthController extends Controller
{
    public function __construct(
        private PermissionTransformerService $transformer
    ) {}
    
    /**
     * GET /api/v1/me
     */
    public function me(Request $request)
    {
        $user = $request->user();
        $account = $user->account;
        
        // Ottieni tutti i permessi (via ruoli + diretti)
        $permissions = $user->getAllPermissions();
        
        // Trasforma in formato CASL
        $abilities = $this->transformer->transformToAbilities($permissions);
        
        return response()->json([
            'user' => new UserResource($user),
            'account' => new AccountResource($account),
            'roles' => RoleResource::collection($user->roles),
            'abilities' => $abilities,
        ]);
    }
}
```

### 11.4 Frontend Angular Setup

```bash
npm install @casl/angular @casl/ability
```

```typescript
// src/app/core/services/ability.service.ts
import { Injectable } from '@angular/core';
import { Ability, AbilityBuilder, AbilityClass, PureAbility } from '@casl/ability';

export type Actions = 'create' | 'read' | 'update' | 'delete' | 'manage' | 'upload';
export type Subjects = 'User' | 'Role' | 'Permission' | 'Media' | 'Setting' | 'all';

export type AppAbility = PureAbility<[Actions, Subjects]>;

@Injectable({ providedIn: 'root' })
export class AbilityService {
  ability = new Ability<[Actions, Subjects]>([]);
  
  updateAbilities(abilities: Array<{ action: Actions; subject: Subjects }>) {
    const { can, rules } = new AbilityBuilder<AppAbility>(Ability as AbilityClass<AppAbility>);
    
    abilities.forEach(rule => {
      can(rule.action, rule.subject);
    });
    
    this.ability.update(rules);
  }
  
  can(action: Actions, subject: Subjects): boolean {
    return this.ability.can(action, subject);
  }
}
```

---

## 12. Timeline e Priorità

### 12.1 Milestone v2.0

| Fase | Componenti | Priorità |
|------|-----------|----------|
| **Phase 1** | Schema DB + Migrations (social_accounts) | 🔴 Alta |
| **Phase 2** | Password Reset + Auth Endpoints | 🔴 Alta |
| **Phase 3** | Media API Refactoring (CRUD completo) | 🔴 Alta |
| **Phase 4** | Laravel Socialite Integration (Google, GitHub) | 🔴 Alta |
| **Phase 5** | OAuth Microsoft/Facebook/Apple | 🟡 Media |
| **Phase 6** | Social Accounts Management API | 🟡 Media |
| **Phase 7** | CASL Integration + `/me` | 🟡 Media |
| **Phase 8** | Testing + Docs | 🔴 Alta |

### 12.2 Deliverables

#### Schema & Migrations
- [ ] Nuova tabella `social_accounts` (Laravel Socialite standard)
- [ ] Migration: `users.password` nullable
- [ ] Migrations v1.7 → v2.0
- [ ] **Tracking Metadata:** Aggiunta `created_by`, `updated_by`, `deleted_by`, `is_deleted` alla tabella `users`
- [ ] **Soft Deletes:** Estensione a tabelle che non lo implementano ancora

#### Permessi & Autorizzazioni
- [ ] **Migration: `standardize_all_permissions_naming`** - Standardizzazione completa permessi database
    - User: `edit_users` → `update:user`, `delete_users` → `delete:user`
    - Setting: `read_settings` → `read:setting`, `update_settings` → `update:setting`
    - Permission: `read_permissions` → `read:permission`
    - Media: `upload_media` → `create:media`, `read_media` → `read:media`
- [ ] **Nuovi permessi Media (CRUD completo):**
    - `create:media` - Crea nuovo media / upload file (v1.7: upload_media)
    - `update:media` - Modifica nome/proprietà media
    - `delete:media` - Eliminazione media
- [ ] **Nuovi permessi Setting (CRUD completo):**
    - `create_setting` - Creazione nuove impostazioni
    - `delete_setting` - Eliminazione impostazioni
- [ ] **Nuovi permessi Permission (CRUD completo):**
    - `create_permission` - Creazione nuovi permessi
    - `update_permission` - Modifica permessi
    - `delete_permission` - Eliminazione permessi
- [ ] **Enum Permessi Aggiornati:**
    - `UserPermission` - Corretti tutti i valori
    - `MediaPermission` - CRUD completo
    - `SettingPermission` - CRUD completo
    - `PermissionPermission` - CRUD completo
- [ ] Assegnazione automatica nuovi permessi al ruolo `admin`
- [ ] Trait `HasTrackingMetadata` per auto-populate campi audit
- [ ] Policy aggiornate per tutti i nuovi permessi

#### OAuth & Authentication
- [ ] **Tabella `password_reset_tokens`** - Sistema recupero password
- [ ] **Nuovi endpoint autenticazione:**
    - `POST /api/v1/logout` - Logout e invalidazione token
    - `POST /api/v1/forgot-password` - Richiesta reset password
    - `POST /api/v1/reset-password` - Reset password con token
    - `PATCH /api/v1/change-password` - Cambio password autenticato
- [ ] **Email Templates:**
    - `password-reset-request` - Email con link reset
    - `password-reset-success` - Conferma reset
    - `password-changed` - Notifica cambio password
- [ ] **OAuth Socialite Integration:**
    - Laravel Socialite core (Google, GitHub, Facebook)
    - SocialiteProviders/Microsoft
    - SocialiteProviders/Apple
- [ ] **Nuovi endpoint OAuth:**
    - `GET /api/v1/oauth/{provider}/redirect` - URL autorizzazione
    - `POST /api/v1/oauth/{provider}/callback` - Callback OAuth
    - `GET /api/v1/me/social-accounts` - Lista account social
    - `POST /api/v1/me/social-accounts/{provider}/link` - Link account
    - `DELETE /api/v1/me/social-accounts/{provider}` - Unlink account
- [ ] **Services:**
    - `PasswordResetService` - Gestione token e validazione
    - `OAuthService` - Gestione OAuth Socialite
    - `SocialAccountService` - CRUD social accounts
    - `EmailNotificationService` - Invio email
- [ ] **Configurazione:**
    - `config/services.php` - OAuth client IDs/secrets
    - `config/factotum_base.php` - OAuth settings (auto_link, sync_profile)
    - `password_reset_token_expiration` - Scadenza token (default: 60 min)
    - `password_reset_throttle` - Rate limiting richieste
- [ ] Permission Transformer Service
- [ ] `/api/v1/me` endpoint con social accounts
- [ ] CASL Angular integration guide

#### Media API Refactoring
- [ ] **Nuovi endpoint Media CRUD:**
    - `GET /api/v1/media/{media}` - Dettaglio JSON (metadati)
    - `PUT /api/v1/media/{media}` - Update nome media
    - `DELETE /api/v1/media/{media}` - Soft delete media
    - `GET /api/v1/media/{media}/download` - Download file binario
    - `GET /api/v1/media/{media}/conversions/{preset}` - Download conversione
- [ ] MediaService: metodi `update()`, `delete()`, `download()`
- [ ] MediaController: azioni CRUD complete
- [ ] MediaResource: response format standardizzato
- [ ] MediaPolicy: autorizzazioni per update/delete
- [ ] Migration: aggiungi tracking metadata a tabella `media`

#### Testing & Documentation
- [ ] Test suite (Unit + Feature)
- [ ] Documentation completa
- [ ] Migration guide per progetti esistenti
- [ ] API documentation Postman/OpenAPI aggiornata

---

## 13. Riferimenti

- [Laravel Socialite Documentation](https://laravel.com/docs/12.x/socialite)
- [CASL Documentation](https://casl.js.org/v6/en/)
- [OAuth 2.0 Specification](https://oauth.net/2/)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
