<div x-data="collaborativeBoard()" x-init="init()" class="h-screen flex flex-col bg-gray-50" @keydown.window="handleKeyPress($event)">
    <!-- Top Toolbar -->
    <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm">
        <!-- Left: Board Name & Tools -->
        <div class="flex items-center space-x-4">
            <input 
                type="text" 
                wire:model.blur="board.name"
                class="text-xl font-semibold border-none focus:ring-2 focus:ring-blue-500 rounded px-2 bg-transparent"
                placeholder="Board Name"
            >
            
            <div class="h-8 w-px bg-gray-300"></div>
            
            <!-- Color & Stroke Controls -->
            <div class="flex items-center space-x-2">
                <input 
                    type="color" 
                    x-model="currentColor"
                    @change="updateColor()"
                    class="w-10 h-10 rounded cursor-pointer border-2 border-gray-300"
                    title="Color"
                >
                <select x-model="strokeWidth" @change="updateStrokeWidth()" class="px-2 py-1 border border-gray-300 rounded text-sm">
                    <option value="1">Thin</option>
                    <option value="2">Normal</option>
                    <option value="4">Thick</option>
                    <option value="8">Very Thick</option>
                </select>
                <button @click="fillMode = !fillMode" :class="fillMode ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'" class="p-2 rounded transition-colors" title="Toggle Fill">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.56 8.94L7.62 0 6.21 1.41l2.38 2.38-5.15 5.15c-.59.59-.59 1.54 0 2.12l5.5 5.5c.29.29.68.44 1.06.44s.77-.15 1.06-.44l5.5-5.5c.59-.58.59-1.53 0-2.12zM5.21 10L10 5.21 14.79 10H5.21zM19 11.5s-2 2.17-2 3.5c0 1.1.9 2 2 2s2-.9 2-2c0-1.33-2-3.5-2-3.5z"/></svg>
                </button>
            </div>
            
            <div class="h-8 w-px bg-gray-300"></div>
            
            <!-- Tool Buttons -->
            <div class="flex items-center space-x-1">
                <button @click="selectTool('select')" :class="selectedTool === 'select' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Select (V)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                </button>
                <button @click="selectTool('pen')" :class="selectedTool === 'pen' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Pen (P)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </button>
                <button @click="selectTool('text')" :class="selectedTool === 'text' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Text (T)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </button>
                <button @click="selectTool('sticky')" :class="selectedTool === 'sticky' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Sticky Note (S)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </button>
                <button @click="selectTool('rectangle')" :class="selectedTool === 'rectangle' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Rectangle (R)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/></svg>
                </button>
                <button @click="selectTool('circle')" :class="selectedTool === 'circle' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Circle (C)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/></svg>
                </button>
                <button @click="selectTool('ellipse')" :class="selectedTool === 'ellipse' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Ellipse (E)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="9" ry="6" stroke-width="2"/></svg>
                </button>
                <button @click="selectTool('triangle')" :class="selectedTool === 'triangle' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Triangle">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l9 18H3L12 3z"/></svg>
                </button>
                <button @click="selectTool('line')" :class="selectedTool === 'line' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Line (L)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19L19 5"/></svg>
                </button>
                <button @click="selectTool('arrow')" :class="selectedTool === 'arrow' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Arrow (A)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
                <button @click="selectTool('mindmap')" :class="selectedTool === 'mindmap' ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100'" class="p-2 rounded-lg transition-colors" title="Mind Map (M)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </button>
            </div>
            
            <div class="h-8 w-px bg-gray-300"></div>
            
            <!-- Undo/Redo -->
            <div class="flex items-center space-x-1">
                <button @click="undo()" :disabled="!canUndo" :class="canUndo ? 'text-gray-700 hover:bg-gray-100' : 'text-gray-300 cursor-not-allowed'" class="p-2 rounded-lg transition-colors" title="Undo (Ctrl+Z)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </button>
                <button @click="redo()" :disabled="!canRedo" :class="canRedo ? 'text-gray-700 hover:bg-gray-100' : 'text-gray-300 cursor-not-allowed'" class="p-2 rounded-lg transition-colors" title="Redo (Ctrl+Y)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/></svg>
                </button>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center space-x-3">
            <!-- Active Collaborators -->
            <div class="flex items-center -space-x-2">
                @foreach($collaborators as $collaborator)
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold border-2 border-white" 
                     title="{{ $collaborator['user']['name'] ?? 'User' }}"
                     style="background: {{ $collaborator['cursor_color'] ?? '#3b82f6' }}">
                    {{ substr($collaborator['user']['name'] ?? 'U', 0, 2) }}
                </div>
                @endforeach
            </div>

            <button @click="exportCanvas('png')" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                Export PNG
            </button>

            <button wire:click="toggleShareModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                <span>Share</span>
            </button>
        </div>
    </div>

    <!-- Canvas Container -->
    <div class="flex-1 relative overflow-hidden bg-white" id="canvas-container">
        <div id="canvas-wrapper" style="transform-origin: center center; transition: transform 0.2s ease;">
            <canvas id="collaborative-canvas"></canvas>
        </div>

        <!-- Zoom Controls -->
        <div class="absolute bottom-4 right-4 bg-white rounded-lg shadow-lg p-2 flex items-center space-x-2 z-10">
            <button @click="zoomOut()" class="p-2 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
            <span class="text-sm font-medium w-12 text-center" x-text="`${Math.round(zoom * 100)}%`"></span>
            <button @click="zoomIn()" class="p-2 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <div class="w-px h-6 bg-gray-300"></div>
            <button @click="resetZoom()" class="p-2 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    <!-- Share Modal -->
    @if($showShareModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center" wire:click="toggleShareModal">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" wire:click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Share Board</h3>
                <button wire:click="toggleShareModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Share Link</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" value="{{ $board->share_url ?? '' }}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        <button onclick="navigator.clipboard.writeText('{{ $board->share_url ?? '' }}')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script>
        function collaborativeBoard() {
            return {
                canvas: null,
                selectedTool: 'select',
                zoom: 1,
                currentColor: '#3b82f6',
                strokeWidth: 2,
                fillMode: false,
                history: [],
                historyStep: -1,
                canUndo: false,
                canRedo: false,

                init() {
                    this.initCanvas();
                    this.loadElements();
                    this.saveState();
                },

                initCanvas() {
                    const canvasContainer = document.getElementById('canvas-container');
                    const canvasEl = document.getElementById('collaborative-canvas');
                    
                    const width = canvasContainer.clientWidth;
                    const height = canvasContainer.clientHeight;
                    
                    canvasEl.width = width;
                    canvasEl.height = height;

                    this.canvas = new fabric.Canvas('collaborative-canvas', {
                        backgroundColor: '#ffffff',
                        selection: true,
                        width: width,
                        height: height,
                    });

                    this.addGrid();
                    this.setupEvents();
                    this.setupPanning();
                    
                    // Handle window resize
                    window.addEventListener('resize', () => {
                        const newWidth = canvasContainer.clientWidth;
                        const newHeight = canvasContainer.clientHeight;
                        this.canvas.setDimensions({ width: newWidth, height: newHeight });
                        canvasEl.width = newWidth;
                        canvasEl.height = newHeight;
                        this.canvas.calcOffset();
                    });
                },

                addGrid() {
                    const gridSize = 20;
                    const width = this.canvas.width;
                    const height = this.canvas.height;

                    for (let i = 0; i < (width / gridSize); i++) {
                        this.canvas.add(new fabric.Line([i * gridSize, 0, i * gridSize, height], {
                            stroke: '#e5e7eb',
                            strokeWidth: 1,
                            selectable: false,
                            evented: false,
                            excludeFromExport: true
                        }));
                    }

                    for (let i = 0; i < (height / gridSize); i++) {
                        this.canvas.add(new fabric.Line([0, i * gridSize, width, i * gridSize], {
                            stroke: '#e5e7eb',
                            strokeWidth: 1,
                            selectable: false,
                            evented: false,
                            excludeFromExport: true
                        }));
                    }
                },

                setupEvents() {
                    // Save state on modifications
                    this.canvas.on('object:modified', () => {
                        this.saveState();
                        this.autoSaveToBackend();
                    });
                    
                    this.canvas.on('object:added', (e) => {
                        if (!e.target.excludeFromExport) {
                            setTimeout(() => {
                                this.saveState();
                                this.autoSaveToBackend();
                            }, 100);
                        }
                    });
                    
                    this.canvas.on('object:removed', () => {
                        this.saveState();
                        this.autoSaveToBackend();
                    });

                    // Path created (pen drawing)
                    this.canvas.on('path:created', (e) => {
                        console.log('Path created:', e.path);
                        setTimeout(() => {
                            this.saveState();
                            this.autoSaveToBackend();
                        }, 100);
                    });

                    // Enable text editing on double click
                    this.canvas.on('mouse:dblclick', (e) => {
                        if (e.target && e.target.type === 'i-text') {
                            e.target.enterEditing();
                        }
                    });
                },

                setupPanning() {
                    let isPanning = false;
                    let lastPosX = 0;
                    let lastPosY = 0;

                    this.canvas.on('mouse:down', (opt) => {
                        const evt = opt.e;
                        if (evt.altKey === true || evt.spaceKey === true) {
                            isPanning = true;
                            this.canvas.selection = false;
                            lastPosX = evt.clientX;
                            lastPosY = evt.clientY;
                        }
                    });

                    this.canvas.on('mouse:move', (opt) => {
                        if (isPanning) {
                            const evt = opt.e;
                            const vpt = this.canvas.viewportTransform;
                            vpt[4] += evt.clientX - lastPosX;
                            vpt[5] += evt.clientY - lastPosY;
                            this.canvas.requestRenderAll();
                            lastPosX = evt.clientX;
                            lastPosY = evt.clientY;
                        }
                    });

                    this.canvas.on('mouse:up', () => {
                        isPanning = false;
                        this.canvas.selection = true;
                    });

                    // Mouse wheel zoom
                    this.canvas.on('mouse:wheel', (opt) => {
                        const delta = opt.e.deltaY;
                        let zoom = this.canvas.getZoom();
                        zoom *= 0.999 ** delta;
                        if (zoom > 3) zoom = 3;
                        if (zoom < 0.1) zoom = 0.1;
                        
                        this.canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
                        this.zoom = zoom;
                        
                        opt.e.preventDefault();
                        opt.e.stopPropagation();
                    });
                },

                selectTool(tool) {
                    this.selectedTool = tool;
                    this.canvas.isDrawingMode = (tool === 'pen');
                    this.canvas.selection = (tool === 'select');
                    
                    if (tool === 'pen') {
                        this.canvas.freeDrawingBrush.width = this.strokeWidth;
                        this.canvas.freeDrawingBrush.color = this.currentColor;
                    }

                    // For shape tools, enable drawing mode
                    if (['rectangle', 'circle', 'ellipse', 'triangle', 'arrow', 'line'].includes(tool)) {
                        this.enableShapeDrawing(tool);
                    } else if (tool === 'text') {
                        this.addText();
                    } else if (tool === 'sticky') {
                        this.addStickyNote();
                    } else if (tool === 'mindmap') {
                        this.addMindMapNode();
                    }
                },

                enableShapeDrawing(shapeType) {
                    let isDrawing = false;
                    let shape = null;
                    let startX, startY;

                    const mouseDown = (o) => {
                        if (this.selectedTool !== shapeType) return;
                        
                        isDrawing = true;
                        const pointer = this.canvas.getPointer(o.e);
                        startX = pointer.x;
                        startY = pointer.y;

                        const fillColor = this.fillMode ? this.currentColor : 'transparent';
                        
                        if (shapeType === 'rectangle') {
                            shape = new fabric.Rect({
                                left: startX,
                                top: startY,
                                width: 0,
                                height: 0,
                                fill: fillColor,
                                stroke: this.currentColor,
                                strokeWidth: this.strokeWidth,
                            });
                        } else if (shapeType === 'circle') {
                            shape = new fabric.Circle({
                                left: startX,
                                top: startY,
                                radius: 0,
                                fill: fillColor,
                                stroke: this.currentColor,
                                strokeWidth: this.strokeWidth,
                            });
                        } else if (shapeType === 'ellipse') {
                            shape = new fabric.Ellipse({
                                left: startX,
                                top: startY,
                                rx: 0,
                                ry: 0,
                                fill: fillColor,
                                stroke: this.currentColor,
                                strokeWidth: this.strokeWidth,
                            });
                        } else if (shapeType === 'triangle') {
                            shape = new fabric.Triangle({
                                left: startX,
                                top: startY,
                                width: 0,
                                height: 0,
                                fill: fillColor,
                                stroke: this.currentColor,
                                strokeWidth: this.strokeWidth,
                            });
                        } else if (shapeType === 'arrow' || shapeType === 'line') {
                            shape = new fabric.Line([startX, startY, startX, startY], {
                                stroke: this.currentColor,
                                strokeWidth: this.strokeWidth + 1,
                            });
                        }

                        if (shape) {
                            this.canvas.add(shape);
                        }
                    };

                    const mouseMove = (o) => {
                        if (!isDrawing || !shape) return;

                        const pointer = this.canvas.getPointer(o.e);

                        if (shapeType === 'rectangle') {
                            const width = pointer.x - startX;
                            const height = pointer.y - startY;
                            
                            if (width < 0) {
                                shape.set({ left: pointer.x });
                            }
                            if (height < 0) {
                                shape.set({ top: pointer.y });
                            }
                            
                            shape.set({
                                width: Math.abs(width),
                                height: Math.abs(height)
                            });
                        } else if (shapeType === 'circle') {
                            const radius = Math.sqrt(
                                Math.pow(pointer.x - startX, 2) + 
                                Math.pow(pointer.y - startY, 2)
                            ) / 2;
                            shape.set({ radius: radius });
                        } else if (shapeType === 'ellipse') {
                            const rx = Math.abs(pointer.x - startX) / 2;
                            const ry = Math.abs(pointer.y - startY) / 2;
                            shape.set({ 
                                rx: rx,
                                ry: ry,
                                left: Math.min(startX, pointer.x) + rx,
                                top: Math.min(startY, pointer.y) + ry
                            });
                        } else if (shapeType === 'triangle') {
                            const width = pointer.x - startX;
                            const height = pointer.y - startY;
                            
                            if (width < 0) {
                                shape.set({ left: pointer.x });
                            }
                            if (height < 0) {
                                shape.set({ top: pointer.y });
                            }
                            
                            shape.set({
                                width: Math.abs(width),
                                height: Math.abs(height)
                            });
                        } else if (shapeType === 'arrow' || shapeType === 'line') {
                            shape.set({ x2: pointer.x, y2: pointer.y });
                        }

                        this.canvas.renderAll();
                    };

                    const mouseUp = (o) => {
                        if (!isDrawing) return;
                        
                        isDrawing = false;
                        
                        // Add arrowhead for arrow tool
                        if (shapeType === 'arrow' && shape) {
                            const pointer = this.canvas.getPointer(o.e);
                            const angle = Math.atan2(pointer.y - startY, pointer.x - startX);
                            
                            const triangle = new fabric.Triangle({
                                left: pointer.x,
                                top: pointer.y,
                                originX: 'center',
                                originY: 'center',
                                width: 15,
                                height: 20,
                                fill: this.currentColor,
                                angle: (angle * 180 / Math.PI) + 90,
                            });

                            const arrow = new fabric.Group([shape, triangle], {
                                selectable: true,
                            });
                            
                            this.canvas.remove(shape);
                            this.canvas.add(arrow);
                            this.canvas.setActiveObject(arrow);
                        } else if (shape) {
                            this.canvas.setActiveObject(shape);
                        }
                        
                        shape = null;
                        
                        // Remove event listeners
                        this.canvas.off('mouse:down', mouseDown);
                        this.canvas.off('mouse:move', mouseMove);
                        this.canvas.off('mouse:up', mouseUp);
                        
                        // Switch back to select tool
                        this.selectTool('select');
                    };

                    this.canvas.on('mouse:down', mouseDown);
                    this.canvas.on('mouse:move', mouseMove);
                    this.canvas.on('mouse:up', mouseUp);
                },

                addText() {
                    const text = new fabric.IText('Click to edit', {
                        left: 100,
                        top: 100,
                        fontSize: 24,
                        fill: this.currentColor,
                        fontFamily: 'Arial',
                    });
                    this.canvas.add(text);
                    this.canvas.setActiveObject(text);
                    text.enterEditing();
                },

                addStickyNote() {
                    const group = new fabric.Group([
                        new fabric.Rect({
                            width: 200,
                            height: 200,
                            fill: '#fef08a',
                            stroke: '#fbbf24',
                            strokeWidth: 2,
                        }),
                        new fabric.IText('Note...', {
                            fontSize: 16,
                            fill: '#1f2937',
                            left: -90,
                            top: -90,
                            fontFamily: 'Arial',
                        })
                    ], {
                        left: 100,
                        top: 100,
                    });
                    this.canvas.add(group);
                    this.canvas.setActiveObject(group);
                },


                addMindMapNode() {
                    const rect = new fabric.Rect({
                        width: 150,
                        height: 80,
                        fill: '#dbeafe',
                        stroke: this.currentColor,
                        strokeWidth: 2,
                        rx: 10,
                        ry: 10,
                    });

                    const text = new fabric.IText('Mind Map', {
                        fontSize: 16,
                        fill: '#1e40af',
                        left: -60,
                        top: -10,
                        fontFamily: 'Arial',
                    });

                    const group = new fabric.Group([rect, text], {
                        left: 100,
                        top: 100,
                    });
                    this.canvas.add(group);
                    this.canvas.setActiveObject(group);
                },

                saveState() {
                    const json = JSON.stringify(this.canvas.toJSON(['excludeFromExport']));
                    this.history = this.history.slice(0, this.historyStep + 1);
                    this.history.push(json);
                    this.historyStep++;
                    this.canUndo = this.historyStep > 0;
                    this.canRedo = false;
                },

                undo() {
                    if (this.historyStep > 0) {
                        this.historyStep--;
                        this.loadFromHistory();
                        this.canUndo = this.historyStep > 0;
                        this.canRedo = true;
                    }
                },

                redo() {
                    if (this.historyStep < this.history.length - 1) {
                        this.historyStep++;
                        this.loadFromHistory();
                        this.canUndo = true;
                        this.canRedo = this.historyStep < this.history.length - 1;
                    }
                },

                loadFromHistory() {
                    this.canvas.loadFromJSON(this.history[this.historyStep], () => {
                        this.canvas.renderAll();
                    });
                },

                loadElements() {
                    // Load saved elements from backend
                    @this.call('loadCanvasData').then(elements => {
                        if (elements && elements.length > 0) {
                            console.log('Loading', elements.length, 'elements from backend');
                            
                            // Clear canvas except grid
                            const objects = this.canvas.getObjects();
                            objects.forEach(obj => {
                                if (!obj.excludeFromExport) {
                                    this.canvas.remove(obj);
                                }
                            });
                            
                            // Load elements
                            elements.forEach(element => {
                                this.addElementToCanvas(element);
                            });
                            
                            this.canvas.requestRenderAll();
                        }
                    });
                },

                addElementToCanvas(element) {
                    try {
                        let obj;
                        
                        // Fix textBaseline for text elements - remove invalid values
                        if (element.type === 'i-text' || element.type === 'text') {
                            delete element.textBaseline; // Remove to use default
                            element.fontFamily = element.fontFamily || 'Arial';
                        }
                        
                        if (element.type === 'rect') {
                            obj = new fabric.Rect(element);
                        } else if (element.type === 'circle') {
                            obj = new fabric.Circle(element);
                        } else if (element.type === 'i-text') {
                            obj = new fabric.IText(element.text || 'Text', element);
                        } else if (element.type === 'path') {
                            obj = new fabric.Path(element.path, element);
                        } else if (element.type === 'line') {
                            obj = new fabric.Line([element.x1, element.y1, element.x2, element.y2], element);
                        } else if (element.type === 'triangle') {
                            obj = new fabric.Triangle(element);
                        } else if (element.type === 'group') {
                            // Handle groups
                            if (element.objects && element.objects.length > 0) {
                                const groupObjects = element.objects.map(subObj => {
                                    // Fix textBaseline for text in groups
                                    if (subObj.type === 'i-text' || subObj.type === 'text') {
                                        delete subObj.textBaseline; // Remove to use default
                                        subObj.fontFamily = subObj.fontFamily || 'Arial';
                                    }
                                    
                                    if (subObj.type === 'rect') return new fabric.Rect(subObj);
                                    if (subObj.type === 'i-text') return new fabric.IText(subObj.text || '', subObj);
                                    if (subObj.type === 'line') return new fabric.Line([subObj.x1, subObj.y1, subObj.x2, subObj.y2], subObj);
                                    if (subObj.type === 'triangle') return new fabric.Triangle(subObj);
                                    return null;
                                }).filter(o => o !== null);
                                
                                obj = new fabric.Group(groupObjects, element);
                            }
                        }
                        
                        if (obj) {
                            this.canvas.add(obj);
                        }
                    } catch (error) {
                        console.error('Error loading element:', error, element);
                    }
                },

                handleKeyPress(event) {
                    if (event.ctrlKey || event.metaKey) {
                        if (event.key === 'z') {
                            event.preventDefault();
                            this.undo();
                        } else if (event.key === 'y') {
                            event.preventDefault();
                            this.redo();
                        }
                    }
                    
                    if (event.key === 'Delete' || event.key === 'Backspace') {
                        const activeObject = this.canvas.getActiveObject();
                        if (activeObject && activeObject.type !== 'i-text') {
                            const activeObjects = this.canvas.getActiveObjects();
                            if (activeObjects.length) {
                                activeObjects.forEach(obj => this.canvas.remove(obj));
                                this.canvas.discardActiveObject();
                                this.canvas.requestRenderAll();
                            }
                        }
                    }
                    
                    // Keyboard shortcuts for tools
                    if (!event.ctrlKey && !event.metaKey) {
                        const activeObject = this.canvas.getActiveObject();
                        if (!activeObject || activeObject.type !== 'i-text' || !activeObject.isEditing) {
                            if (event.key === 'v' || event.key === 'V') this.selectTool('select');
                            else if (event.key === 'p' || event.key === 'P') this.selectTool('pen');
                            else if (event.key === 't' || event.key === 'T') this.selectTool('text');
                            else if (event.key === 's' || event.key === 'S') this.selectTool('sticky');
                            else if (event.key === 'r' || event.key === 'R') this.selectTool('rectangle');
                            else if (event.key === 'c' || event.key === 'C') this.selectTool('circle');
                            else if (event.key === 'e' || event.key === 'E') this.selectTool('ellipse');
                            else if (event.key === 'l' || event.key === 'L') this.selectTool('line');
                            else if (event.key === 'a' || event.key === 'A') this.selectTool('arrow');
                            else if (event.key === 'm' || event.key === 'M') this.selectTool('mindmap');
                        }
                    }
                },

                zoomIn() {
                    this.zoom = Math.min(this.zoom * 1.2, 3);
                    const center = this.canvas.getCenter();
                    this.canvas.zoomToPoint(new fabric.Point(center.left, center.top), this.zoom);
                },

                zoomOut() {
                    this.zoom = Math.max(this.zoom * 0.8, 0.1);
                    const center = this.canvas.getCenter();
                    this.canvas.zoomToPoint(new fabric.Point(center.left, center.top), this.zoom);
                },

                resetZoom() {
                    this.zoom = 1;
                    this.canvas.setZoom(1);
                    this.canvas.viewportTransform = [1, 0, 0, 1, 0, 0];
                    this.canvas.requestRenderAll();
                },

                exportCanvas(format) {
                    if (format === 'png') {
                        const dataURL = this.canvas.toDataURL({
                            format: 'png',
                            quality: 1,
                            multiplier: 2
                        });
                        const link = document.createElement('a');
                        link.download = 'board-export.png';
                        link.href = dataURL;
                        link.click();
                    }
                },

                autoSaveToBackend() {
                    // Debounce auto-save
                    clearTimeout(this.saveTimeout);
                    this.saveTimeout = setTimeout(() => {
                        const json = this.canvas.toJSON();
                        const elements = json.objects.filter(obj => !obj.excludeFromExport);
                        
                        console.log('Auto-saving to backend:', elements.length, 'objects');
                        
                        // Save to backend via Livewire
                        @this.call('saveCanvasData', JSON.stringify(elements));
                    }, 1000);
                },

                updateColor() {
                    if (this.canvas.isDrawingMode) {
                        this.canvas.freeDrawingBrush.color = this.currentColor;
                    }
                    
                    const activeObjects = this.canvas.getActiveObjects();
                    activeObjects.forEach(obj => {
                        if (obj.type === 'path') {
                            obj.set('stroke', this.currentColor);
                        } else if (obj.type === 'i-text' || obj.type === 'text') {
                            obj.set('fill', this.currentColor);
                        } else {
                            obj.set('fill', this.currentColor);
                            if (obj.stroke) {
                                obj.set('stroke', this.currentColor);
                            }
                        }
                    });
                    this.canvas.requestRenderAll();
                    this.saveState();
                },

                updateStrokeWidth() {
                    if (this.canvas.isDrawingMode) {
                        this.canvas.freeDrawingBrush.width = parseInt(this.strokeWidth);
                    }
                    
                    const activeObjects = this.canvas.getActiveObjects();
                    activeObjects.forEach(obj => {
                        obj.set('strokeWidth', parseInt(this.strokeWidth));
                    });
                    this.canvas.requestRenderAll();
                    this.saveState();
                },
            };
        }
    </script>
    @endpush
</div>
