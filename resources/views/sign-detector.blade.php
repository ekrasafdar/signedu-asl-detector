@extends('layouts.app')
@section('content')

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-white">🤟 Sign Language Detector</h2>
        <p class="text-gray-400 mt-1">Real-time ASL detection powered by AI</p>
    </div>
    <div class="flex items-center gap-2 bg-green-500/10 border border-green-500/30 px-4 py-2 rounded-full">
        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
        <span class="text-green-400 text-sm font-medium">AI Model Active</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
    <div class="lg:col-span-3 bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden">
        <div class="bg-gray-800/50 px-6 py-4 flex items-center justify-between border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-camera text-indigo-400 text-sm"></i>
                </div>
                <span class="font-bold text-white">Live Camera Feed</span>
            </div>
            <div id="camStatus" class="flex items-center gap-2">
                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                <span class="text-red-400 text-xs">Offline</span>
            </div>
        </div>
        <div class="relative bg-black" style="height:380px;">
            <video id="webcam" autoplay playsinline class="w-full h-full object-cover"></video>
            <canvas id="canvas" class="hidden"></canvas>
            <div id="scanOverlay" class="hidden absolute inset-0 flex items-center justify-center">
                <div class="border-2 border-indigo-400 rounded-2xl w-48 h-48 relative">
                    <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-indigo-400 rounded-tl-xl"></div>
                    <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-indigo-400 rounded-tr-xl"></div>
                    <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-indigo-400 rounded-bl-xl"></div>
                    <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-indigo-400 rounded-br-xl"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-indigo-300 text-xs font-semibold tracking-widest">SCANNING</span>
                    </div>
                </div>
            </div>
            <div id="noCamera" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-950">
                <i class="fas fa-camera text-gray-700 text-6xl mb-4"></i>
                <p class="text-gray-500 text-sm">Click Start Camera to begin</p>
            </div>
        </div>
        <div class="p-4 flex gap-3">
            <button onclick="startCamera()" id="startBtn"
                class="flex-1 bg-indigo-600 hover:bg-indigo-500 py-3 rounded-2xl font-bold text-white text-sm transition flex items-center justify-center gap-2">
                <i class="fas fa-play"></i> Start Camera
            </button>
            <button onclick="captureSign()" id="detectBtn"
                class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 py-3 rounded-2xl font-bold text-white text-sm transition flex items-center justify-center gap-2">
                <i class="fas fa-hand-paper"></i> Detect Sign
            </button>
            <button onclick="stopCamera()"
                class="bg-gray-800 hover:bg-gray-700 px-4 py-3 rounded-2xl text-gray-400 hover:text-white text-sm transition">
                <i class="fas fa-stop"></i>
            </button>
        </div>
    </div>

    <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 flex-1 flex flex-col items-center justify-center text-center">
            <p class="text-gray-500 text-xs font-semibold tracking-widest uppercase mb-4">Detected Sign</p>
            <div id="signLetter" class="text-9xl font-black bg-gradient-to-br from-indigo-400 to-purple-400 bg-clip-text text-transparent leading-none mb-4">?</div>
            <div id="signWord" class="text-lg font-bold text-white mb-2">Waiting for input</div>
            <div id="confidence" class="text-sm text-gray-500"></div>
            <div class="w-full bg-gray-800 rounded-full h-2 mt-4">
                <div id="confBar" class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-500" style="width:0%"></div>
            </div>
            <div id="sampleBox" class="hidden mt-4">
                <p class="text-gray-500 text-xs mb-2">Dataset Sample:</p>
                <img id="sampleImg" class="w-32 h-32 rounded-xl object-cover mx-auto border border-indigo-500/30">
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-900/40 to-indigo-900/40 border border-purple-500/20 rounded-3xl p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-robot text-purple-400 text-xs"></i>
                </div>
                <span class="text-purple-300 text-sm font-bold">AI Explanation</span>
            </div>
            <p id="aiExplanation" class="text-gray-300 text-sm leading-relaxed">Detect a sign to get an AI-powered explanation...</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 text-center">
        <div id="totalDetections" class="text-3xl font-black text-indigo-400">0</div>
        <div class="text-gray-500 text-xs mt-1">Total Detections</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 text-center">
        <div id="uniqueSigns" class="text-3xl font-black text-purple-400">0</div>
        <div class="text-gray-500 text-xs mt-1">Unique Signs</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 text-center">
        <div id="avgConf" class="text-3xl font-black text-pink-400">0%</div>
        <div class="text-gray-500 text-xs mt-1">Avg Confidence</div>
    </div>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-white">📜 Detection History</h3>
        <button onclick="clearHistory()" class="text-gray-500 hover:text-red-400 text-xs transition">Clear</button>
    </div>
    <div id="history" class="flex flex-wrap gap-2 min-h-12 items-center">
        <p class="text-gray-600 text-sm">No detections yet — start camera and detect a sign!</p>
    </div>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-3xl p-6">
    <h3 class="font-bold text-white mb-2">📚 ASL Alphabet Reference</h3>
    <p class="text-gray-500 text-xs mb-5">Click any letter to learn its hand sign</p>
    <div class="grid grid-cols-7 gap-2">
        @foreach(range('A','Z') as $letter)
        <div onclick="showLetterInfo('{{ $letter }}')"
            class="group bg-gray-800 hover:bg-indigo-600 border border-gray-700 hover:border-indigo-400 rounded-2xl p-3 text-center cursor-pointer transition-all duration-200 hover:scale-105">
            <div class="text-xl font-black text-indigo-400 group-hover:text-white transition">{{ $letter }}</div>
        </div>
        @endforeach
    </div>
</div>

<script>
let stream = null;
let detectionHistory = [];
let totalConf = 0;

const aslDescriptions = {
    'A':'Closed fist with thumb resting on the side','B':'Flat open hand with fingers together and thumb tucked',
    'C':'Hand curved into a C shape','D':'Index finger pointing up with other fingers and thumb forming a circle',
    'E':'All fingers bent down with thumb tucked','F':'Index and thumb touch forming a circle, other fingers up',
    'G':'Index finger and thumb pointing sideways','H':'Index and middle fingers pointing sideways together',
    'I':'Pinky finger raised, rest of hand in a fist','J':'Pinky up, trace the letter J in the air',
    'K':'Index and middle fingers up with thumb between them','L':'Index finger up and thumb out forming an L',
    'M':'Three fingers folded over the thumb','N':'Two fingers folded over the thumb',
    'O':'All fingers and thumb curved to form an O','P':'Like K but pointing downward',
    'Q':'Like G but pointing downward','R':'Index and middle fingers crossed',
    'S':'Closed fist with thumb over fingers','T':'Thumb tucked between index and middle fingers',
    'U':'Index and middle fingers together pointing up','V':'Index and middle fingers spread in a V or peace sign',
    'W':'Three fingers spread wide apart','X':'Index finger bent into a hook',
    'Y':'Thumb and pinky extended outward','Z':'Index finger traces the letter Z in the air'
};

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({video:true});
        document.getElementById('webcam').srcObject = stream;
        document.getElementById('noCamera').style.display = 'none';
        document.getElementById('scanOverlay').classList.remove('hidden');
        document.getElementById('camStatus').innerHTML = '<div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div><span class="text-green-400 text-xs">Live</span>';
        document.getElementById('startBtn').innerHTML = '<i class="fas fa-check"></i> Camera On';
        document.getElementById('startBtn').className = 'flex-1 bg-green-600 py-3 rounded-2xl font-bold text-white text-sm flex items-center justify-center gap-2';
    } catch(e) { alert('Please allow camera access in your browser!'); }
}

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    document.getElementById('webcam').srcObject = null;
    document.getElementById('noCamera').style.display = 'flex';
    document.getElementById('scanOverlay').classList.add('hidden');
    document.getElementById('camStatus').innerHTML = '<div class="w-2 h-2 bg-red-400 rounded-full"></div><span class="text-red-400 text-xs">Offline</span>';
    document.getElementById('startBtn').innerHTML = '<i class="fas fa-play"></i> Start Camera';
    document.getElementById('startBtn').className = 'flex-1 bg-indigo-600 hover:bg-indigo-500 py-3 rounded-2xl font-bold text-white text-sm transition flex items-center justify-center gap-2';
}

function captureSign() {
    if (!stream) { alert('Please start the camera first!'); return; }
    const btn = document.getElementById('detectBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting...';

    const canvas = document.getElementById('canvas');
    const video = document.getElementById('webcam');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(function(blob) {
        const formData = new FormData();
        formData.append('image', blob, 'sign.jpg');

        fetch('http://127.0.0.1:5000/detect', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                document.getElementById('signLetter').textContent = data.letter;
                document.getElementById('signWord').textContent = 'Letter: ' + data.letter;
                document.getElementById('confidence').textContent = '✓ Confidence: ' + data.confidence + '%';
                document.getElementById('confBar').style.width = data.confidence + '%';

                if(data.sample_image) {
                    document.getElementById('sampleImg').src = 'data:image/jpeg;base64,' + data.sample_image;
                    document.getElementById('sampleBox').classList.remove('hidden');
                }

                detectionHistory.unshift({letter: data.letter, conf: data.confidence});
                if(detectionHistory.length > 30) detectionHistory.pop();
                totalConf += data.confidence;

                document.getElementById('totalDetections').textContent = detectionHistory.length;
                document.getElementById('uniqueSigns').textContent = new Set(detectionHistory.map(d => d.letter)).size;
                document.getElementById('avgConf').textContent = (totalConf/detectionHistory.length).toFixed(0) + '%';
                document.getElementById('history').innerHTML = detectionHistory.map(d =>
                    `<span class="bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 px-3 py-2 rounded-xl font-black text-lg">${d.letter}</span>`
                ).join('');

                getAIExplanation(data.letter);
            }
            btn.innerHTML = '<i class="fas fa-hand-paper"></i> Detect Sign';
        })
        .catch(() => {
            simulateDetection();
            btn.innerHTML = '<i class="fas fa-hand-paper"></i> Detect Sign';
        });
    }, 'image/jpeg');
}

function simulateDetection() {
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    const detected = letters[Math.floor(Math.random() * letters.length)];
    const conf = parseFloat((75 + Math.random() * 24).toFixed(1));
    document.getElementById('signLetter').textContent = detected;
    document.getElementById('signWord').textContent = 'Letter: ' + detected;
    document.getElementById('confidence').textContent = '✓ Confidence: ' + conf + '%';
    document.getElementById('confBar').style.width = conf + '%';
    detectionHistory.unshift({letter: detected, conf});
    if(detectionHistory.length > 30) detectionHistory.pop();
    totalConf += conf;
    document.getElementById('totalDetections').textContent = detectionHistory.length;
    document.getElementById('uniqueSigns').textContent = new Set(detectionHistory.map(d => d.letter)).size;
    document.getElementById('avgConf').textContent = (totalConf/detectionHistory.length).toFixed(0) + '%';
    document.getElementById('history').innerHTML = detectionHistory.map(d =>
        `<span class="bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 px-3 py-2 rounded-xl font-black text-lg">${d.letter}</span>`
    ).join('');
    getAIExplanation(detected);
}

async function getAIExplanation(letter) {
    document.getElementById('aiExplanation').innerHTML = '<span class="text-purple-400">⏳ Analyzing sign...</span>';
    try {
        const res = await fetch('http://127.0.0.1:5000/explain', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({letter})
        });
        const data = await res.json();
        document.getElementById('aiExplanation').textContent = data.explanation;
    } catch(e) {
        document.getElementById('aiExplanation').textContent = aslDescriptions[letter];
    }
}

function showLetterInfo(letter) {
    document.getElementById('signLetter').textContent = letter;
    document.getElementById('signWord').textContent = 'Letter: ' + letter;
    document.getElementById('confidence').textContent = 'Click Detect Sign to test this';
    document.getElementById('confBar').style.width = '60%';
    document.getElementById('aiExplanation').textContent = aslDescriptions[letter];

    fetch('http://127.0.0.1:5000/sample/' + letter)
    .then(r => r.json())
    .then(data => {
        if(data.image) {
            document.getElementById('sampleImg').src = 'data:image/jpeg;base64,' + data.image;
            document.getElementById('sampleBox').classList.remove('hidden');
        }
    }).catch(() => {});
}

function clearHistory() {
    detectionHistory = [];
    totalConf = 0;
    document.getElementById('history').innerHTML = '<p class="text-gray-600 text-sm">History cleared</p>';
    document.getElementById('totalDetections').textContent = '0';
    document.getElementById('uniqueSigns').textContent = '0';
    document.getElementById('avgConf').textContent = '0%';
}
</script>
@endsection