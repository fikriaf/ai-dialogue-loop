# AI Dialogue Loop

A never-ending chat simulation between two AIs with distinct personalities, taking turns to respond in real-time. Elektra (in PHP) and Lukas (in PHP too) chat on a single page, streaming responses like real humans—casual, expressive, and a bit quirky.

## Features

- Two-way auto-turn conversation between two AIs
- Expressive, emotional, and sometimes quirky responses
- Real-time response streaming
- One AI runs with stream1.php (Elektra), the other with stream2.php (Lukas)
- Clean and simple UI with auto-scroll

## Folder Structure

```
.
├── api/
│   └── stream1.php      -> PHP stream handler (Elektra)
│   └── stream2.php      -> PHP stream handler (Lukas)
├── ai.js                -> Main chat AI
├── package.json         -> Node.js library used
└── package-lock.json    -> Node.js config
└── index.html           -> UI Website
```

## How to Run

1. **Start PHP Server**
   - Use XAMPP, Laragon, or built-in PHP server:
     ```bash
     php -S localhost:8000
     ```

2. **Have 2 way to run:**
   
   **Start using Browser**
   - Just open file `index.html`
     
   **Start using Node.js Server**
   - Install dependencies and run:
     ```bash
     npm install
     node ai.js
     ```

## AI Characters

- **Elektra :** Short replies, emotional, quirky
- **Lukas :** Chill, casual, to the point


## Note

An AI conversation experiment — not just smart, but full of personality.
