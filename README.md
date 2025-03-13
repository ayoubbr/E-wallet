# Documentation de l'API REST

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentification
Toutes les routes protégées nécessitent un token d'authentification via Laravel Sanctum.

---

## Endpoints

### 1. Authentification

#### Inscription
- **URL**: `/register`
- **Méthode**: `POST`
- **Paramètres** (Body JSON):
  ```json
  {
    "firstname": "John",
    "lastname": "Doe",
    "email": "john.doe@example.com",
    "password": "secret123",
    "role_name": "user"
  }
  ```
- **Réponse**:
  ```json
  {
    "message": "User Created with wallet and role."
  }
  ```

#### Connexion
- **URL**: `/login`
- **Méthode**: `POST`
- **Paramètres** (Body JSON):
  ```json
  {
    "email": "john.doe@example.com",
    "password": "secret123"
  }
  ```
- **Réponse**:
  ```json
  {
    "access_token": "your_token_here"
  }
  ```

#### Déconnexion
- **URL**: `/logout`
- **Méthode**: `POST`
- **Headers**:  
  - `Authorization: Bearer {access_token}`
- **Réponse**:
  ```json
  {
    "message": "logged out"
  }
  ```

---

### 2. Transferts

#### Effectuer un transfert
- **URL**: `/transfers`
- **Méthode**: `POST`
- **Headers**:  
  - `Authorization: Bearer {access_token}`
- **Paramètres** (Body JSON):
  ```json
  {
    "amount": 100.50,
    "receiver_email": "receiver@example.com",
    "receiver_firstname": "Jane",
    "receiver_lastname": "Doe"
  }
  ```
- **Réponse**:
  ```json
  {
    "sender_wallet": {...},
    "receiver_wallet": {...}
  }
  ```

#### Annuler un transfert
- **URL**: `/transfers/rollback`
- **Méthode**: `POST`
- **Headers**:  
  - `Authorization: Bearer {access_token}`
- **Paramètres** (Body JSON):
  ```json
  {
    "serial": "ABC123XYZ"
  }
  ```
- **Réponse**:
  ```json
  {
    "transfer": {...}
  }
  ```

---

### 3. Wallet

#### Dépôt d'argent
- **URL**: `/wallets/deposit`
- **Méthode**: `POST`
- **Headers**:  
  - `Authorization: Bearer {access_token}`
- **Paramètres** (Body JSON):
  ```json
  {
    "amount": 200.00
  }
  ```
- **Réponse**:
  ```json
  {
    "sender_wallet": {...},
    "transfer": {...}
  }
  ```

#### Retrait d'argent
- **URL**: `/wallets/withdraw`
- **Méthode**: `POST`
- **Headers**:  
  - `Authorization: Bearer {access_token}`
- **Paramètres** (Body JSON):
  ```json
  {
    "amount": 50.00
  }
  ```
- **Réponse**:
  ```json
  {
    "sender_wallet": {...},
    "transfer": {...}
  }
  ```

---

## Sécurité
- L'authentification se fait via Laravel Sanctum.
- Les transactions sont protégées par des vérifications de solde et des vérifications d'utilisateur.

---
