<div>
    <div class="h-screen flex flex-col bg-gray-50">
        <!-- Toolbar -->
        <div class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-gray-900">Drawing Board</h1>
                    
                    <div class="h-8 w-px bg-gray-300"></div>
                    
                    <!-- Tools -->
                    <div class="flex items-center space-x-2">
                        <button onclick="selectTool('move')" id="tool-move" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Move/Pan (Space)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        </button>
                        <button onclick="selectTool('pen')" id="tool-pen" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Pen (P)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <button onclick="selectTool('eraser')" id="tool-eraser" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Eraser (E)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <button onclick="selectTool('text')" id="tool-text" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Text (T)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </button>
                        <button onclick="selectTool('rectangle')" id="tool-rectangle" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Rectangle (R)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/></svg>
                        </button>
                        <button onclick="selectTool('circle')" id="tool-circle" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Circle (C)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/></svg>
                        </button>
                        <button onclick="selectTool('line')" id="tool-line" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Line (L)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19L19 5"/></svg>
                        </button>
                    </div>
                    
                    <div class="h-8 w-px bg-gray-300"></div>
                    
                    <!-- Color & Size -->
                    <input type="color" id="colorPicker" value="#000000" class="w-10 h-10 rounded cursor-pointer border-2 border-gray-300" title="Color">
                    
                    <!-- Manual Size Input -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600">Size:</label>
                        <input type="number" id="brushSizeInput" value="5" min="1" max="100" class="w-16 px-2 py-1 border border-gray-300 rounded text-sm" onchange="updateBrushSize()">
                        <input type="range" id="brushSizeSlider" value="5" min="1" max="100" class="w-24" oninput="updateBrushSizeFromSlider()">
                    </div>
                    
                    <div class="h-8 w-px bg-gray-300"></div>
                    
                    <!-- Zoom Controls -->
                    <div class="flex items-center space-x-1">
                        <button onclick="zoomIn()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Zoom In (+)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                        </button>
                        <span id="zoomLevel" class="text-sm text-gray-600 w-12 text-center">100%</span>
                        <button onclick="zoomOut()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Zoom Out (-)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                        </button>
                        <button onclick="resetZoom()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Reset Zoom (0)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                    
                    <div class="h-8 w-px bg-gray-300"></div>
                    
                    <!-- Actions -->
                    <button onclick="undoLast()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Undo (Ctrl+Z)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    </button>
                    <button onclick="clearCanvas()" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm">
                        Clear All
                    </button>
                    <button onclick="saveCanvas()" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Save
                    </button>
                </div>
            </div>
        </div>

        <!-- Canvas -->
        <div class="flex-1 relative overflow-hidden bg-white">
            <canvas id="drawingCanvas" class="border border-gray-200"></canvas>
        </div>
    </div>

    @push('scripts')
    <script>
let canvas, ctx;
let isDrawing = false;
let currentTool = 'pen';
let currentColor = '#000000';
let brushSize = 5;
let startX, startY;
let snapshot;
let scale = 1;
let offsetX = 0;
let offsetY = 0;
let isPanning = false;
let panStartX, panStartY;
let history = [];
let historyStep = -1;

window.addEventListener('DOMContentLoaded', () => {
    canvas = document.getElementById('drawingCanvas');
    ctx = canvas.getContext('2d');
    
    // Set canvas size
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Mouse events
    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDraw);
    canvas.addEventListener('mouseout', stopDraw);
    
    // Touch events for mobile
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDraw);
    
    // Color and size
    document.getElementById('colorPicker').addEventListener('change', (e) => {
        currentColor = e.target.value;
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'p' || e.key === 'P') selectTool('pen');
        if (e.key === 'e' || e.key === 'E') selectTool('eraser');
        if (e.key === 't' || e.key === 'T') selectTool('text');
        if (e.key === 'r' || e.key === 'R') selectTool('rectangle');
        if (e.key === 'c' || e.key === 'C') selectTool('circle');
        if (e.key === 'l' || e.key === 'L') selectTool('line');
        if (e.key === ' ') { selectTool('move'); e.preventDefault(); }
        if (e.key === '+' || e.key === '=') zoomIn();
        if (e.key === '-' || e.key === '_') zoomOut();
        if (e.key === '0') resetZoom();
        if (e.ctrlKey && e.key === 'z') { undoLast(); e.preventDefault(); }
    });
    
    // Mouse wheel zoom
    canvas.addEventListener('wheel', (e) => {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    });
    
    // Select pen tool by default
    selectTool('pen');
    saveState();
});

function resizeCanvas() {
    const container = canvas.parentElement;
    canvas.width = container.clientWidth;
    canvas.height = container.clientHeight;
}

function selectTool(tool) {
    currentTool = tool;
    
    // Update button styles
    document.querySelectorAll('[id^="tool-"]').forEach(btn => {
        btn.classList.remove('bg-blue-100', 'text-blue-600');
        btn.classList.add('text-gray-600');
    });
    
    const selectedBtn = document.getElementById('tool-' + tool);
    if (selectedBtn) {
        selectedBtn.classList.add('bg-blue-100', 'text-blue-600');
        selectedBtn.classList.remove('text-gray-600');
    }
    
    // Update cursor
    if (tool === 'move') {
        canvas.style.cursor = 'grab';
    } else if (tool === 'eraser') {
        canvas.style.cursor = 'not-allowed';
    } else if (tool === 'text') {
        canvas.style.cursor = 'text';
    } else {
        canvas.style.cursor = 'crosshair';
    }
}

function startDraw(e) {
    const rect = canvas.getBoundingClientRect();
    startX = e.clientX - rect.left;
    startY = e.clientY - rect.top;
    
    if (currentTool === 'move') {
        isPanning = true;
        panStartX = startX - offsetX;
        panStartY = startY - offsetY;
        canvas.style.cursor = 'grabbing';
        return;
    }
    
    isDrawing = true;
    
    // Save canvas state for shape drawing
    if (['rectangle', 'circle', 'line'].includes(currentTool)) {
        snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height);
    }
    
    if (currentTool === 'text') {
        addText(startX, startY);
        isDrawing = false;
    }
}

function draw(e) {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    if (isPanning) {
        offsetX = x - panStartX;
        offsetY = y - panStartY;
        redrawCanvas();
        return;
    }
    
    if (!isDrawing) return;
    
    ctx.strokeStyle = currentColor;
    ctx.fillStyle = currentColor;
    ctx.lineWidth = brushSize;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    
    if (currentTool === 'pen') {
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    } else if (currentTool === 'eraser') {
        ctx.clearRect(x - brushSize/2, y - brushSize/2, brushSize, brushSize);
    } else if (currentTool === 'rectangle') {
        ctx.putImageData(snapshot, 0, 0);
        const width = x - startX;
        const height = y - startY;
        ctx.strokeRect(startX, startY, width, height);
    } else if (currentTool === 'circle') {
        ctx.putImageData(snapshot, 0, 0);
        const radius = Math.sqrt(Math.pow(x - startX, 2) + Math.pow(y - startY, 2));
        ctx.beginPath();
        ctx.arc(startX, startY, radius, 0, 2 * Math.PI);
        ctx.stroke();
    } else if (currentTool === 'line') {
        ctx.putImageData(snapshot, 0, 0);
        ctx.beginPath();
        ctx.moveTo(startX, startY);
        ctx.lineTo(x, y);
        ctx.stroke();
    }
}

function stopDraw() {
    if (isDrawing) {
        isDrawing = false;
        ctx.beginPath();
        saveState();
    }
    if (isPanning) {
        isPanning = false;
        if (currentTool === 'move') {
            canvas.style.cursor = 'grab';
        }
    }
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                     e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}

function addText(x, y) {
    const text = prompt('Enter text:');
    if (text) {
        ctx.font = `${brushSize * 4}px Arial`;
        ctx.fillStyle = currentColor;
        ctx.fillText(text, x, y);
    }
}

function clearCanvas() {
    if (confirm('Clear entire canvas?')) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
}

function saveCanvas() {
    const dataURL = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.download = 'drawing-' + Date.now() + '.png';
    link.href = dataURL;
    link.click();
    alert('Drawing saved!');
}

// Size adjustment functions
function updateBrushSize() {
    brushSize = parseInt(document.getElementById('brushSizeInput').value);
    document.getElementById('brushSizeSlider').value = brushSize;
}

function updateBrushSizeFromSlider() {
    brushSize = parseInt(document.getElementById('brushSizeSlider').value);
    document.getElementById('brushSizeInput').value = brushSize;
}

// Zoom functions
function zoomIn() {
    scale = Math.min(scale + 0.1, 5);
    updateZoom();
}

function zoomOut() {
    scale = Math.max(scale - 0.1, 0.1);
    updateZoom();
}

function resetZoom() {
    scale = 1;
    offsetX = 0;
    offsetY = 0;
    updateZoom();
}

function updateZoom() {
    document.getElementById('zoomLevel').textContent = Math.round(scale * 100) + '%';
    redrawCanvas();
}

function redrawCanvas() {
    ctx.setTransform(scale, 0, 0, scale, offsetX, offsetY);
}

// Undo function
function saveState() {
    historyStep++;
    if (historyStep < history.length) {
        history.length = historyStep;
    }
    history.push(canvas.toDataURL());
}

function undoLast() {
    if (historyStep > 0) {
        historyStep--;
        const img = new Image();
        img.src = history[historyStep];
        img.onload = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
        };
    }
}
    </script>
    @endpush

    @push('styles')
    <style>
#drawingCanvas {
    cursor: crosshair;
    display: block;
}
    </style>
    @endpush
</div>
