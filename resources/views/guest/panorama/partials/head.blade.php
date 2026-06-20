<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>360 Virtual Tour - {{ $situs->nama }} | Smart Prasada</title>
    <script src="https://aframe.io/releases/1.5.0/aframe.min.js"></script>
    <script src="https://unpkg.com/aframe-look-at-component@0.8.0/dist/aframe-look-at-component.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])

    <style>
        body, html {
            width: 100%; height: 100%; margin: 0; padding: 0;
            overflow: hidden; background-color: #000;
            font-family: 'Inter', sans-serif;
        }
        #ui-layer {
            position: absolute; inset: 0; pointer-events: none; z-index: 10;
            display: flex; flex-direction: column;
            justify-content: space-between; padding: 1rem;
        }
        .interactive { pointer-events: auto; }
        #header-bar { display: flex; justify-content: space-between; align-items: flex-start; }
        .btn-circle {
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);
            color: white; border: 1px solid rgba(255,255,255,0.2);
            cursor: pointer; transition: all 0.2s ease;
        }
        .btn-circle:hover { background: rgba(255,255,255,0.2); transform: scale(1.05); }
        .tour-title-box {
            background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);
            padding: 0.75rem 1.5rem; border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.2); color: white; text-align: center;
        }
        #loading-overlay {
            position: absolute; inset: 0; background: #000; z-index: 50;
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; color: white; transition: opacity 0.5s ease;
        }
        .spinner {
            width: 40px; height: 40px;
            border: 4px solid rgba(255,255,255,0.1); border-top-color: #0ea5e9;
            border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 1rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        #info-modal {
            position: absolute; inset: 0; background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px); z-index: 40;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease; padding: 1rem;
        }
        #info-modal.active { opacity: 1; pointer-events: auto; }
        .modal-card {
            background: white; border-radius: 1rem; width: 100%; max-width: 28rem;
            overflow: hidden; transform: translateY(20px);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex; flex-direction: column; max-height: 90vh;
        }
        #info-modal.active .modal-card { transform: translateY(0); }
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        }
        .modal-body { padding: 1.5rem; overflow-y: auto; }
        .modal-img {
            width: 100%; height: auto; max-height: 200px; object-fit: cover;
            border-radius: 0.5rem; margin-bottom: 1rem; display: none;
        }
        #transition-overlay {
            position: absolute; inset: 0; background: black; z-index: 5;
            opacity: 0; pointer-events: none; transition: opacity 0.4s ease;
        }
    </style>
</head>
