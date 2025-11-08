<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\BoardElement;
use App\Models\BoardCollaborator;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CollaborativeBoard extends Component
{
    public $boardId;
    public $board;
    public $elements = [];
    public $collaborators = [];
    public $selectedTool = 'select';
    public $showShareModal = false;
    public $isPresentationMode = false;
    
    // Drawing properties
    public $currentColor = '#3b82f6';
    public $currentStrokeWidth = 2;
    public $currentFontSize = 16;

    protected $listeners = [
        'elementCreated' => 'handleElementCreated',
        'elementUpdated' => 'handleElementUpdated',
        'elementDeleted' => 'handleElementDeleted',
        'cursorMoved' => 'handleCursorMoved',
    ];

    public function mount($boardId = null)
    {
        if ($boardId) {
            $this->board = Board::with(['elements', 'collaborators.user'])->findOrFail($boardId);
            $this->boardId = $this->board->id;
            $this->elements = $this->board->elements->toArray();
            $this->collaborators = $this->board->collaborators->toArray();
            
            // Add current user as collaborator if not exists
            $this->joinBoard();
        } else {
            // Create new board
            $this->createNewBoard();
        }
    }

    public function createNewBoard()
    {
        $this->board = Board::create([
            'name' => 'Untitled Board',
            'description' => '',
            'created_by' => Auth::id(),
            'settings' => [
                'grid_enabled' => true,
                'grid_size' => 20,
                'snap_to_grid' => false,
            ],
        ]);

        $this->boardId = $this->board->id;
        $this->joinBoard();
    }

    public function joinBoard()
    {
        BoardCollaborator::updateOrCreate(
            [
                'board_id' => $this->boardId,
                'user_id' => Auth::id(),
            ],
            [
                'role' => $this->board->created_by === Auth::id() ? 'owner' : 'editor',
                'last_seen_at' => now(),
            ]
        );

        $this->refreshCollaborators();
    }

    public function selectTool($tool)
    {
        $this->selectedTool = $tool;
    }

    public function createElement($type, $properties)
    {
        $element = BoardElement::create([
            'board_id' => $this->boardId,
            'type' => $type,
            'properties' => $properties,
            'z_index' => $this->getNextZIndex(),
            'created_by' => Auth::id(),
        ]);

        $this->elements[] = $element->toArray();
        
        // Broadcast to other users
        $this->dispatch('element-created', element: $element->toArray());
        
        return $element;
    }

    public function updateElement($elementId, $properties)
    {
        $element = BoardElement::find($elementId);
        
        if ($element && $element->board_id === $this->boardId) {
            $element->update(['properties' => $properties]);
            
            // Update local state
            $index = collect($this->elements)->search(fn($e) => $e['id'] === $elementId);
            if ($index !== false) {
                $this->elements[$index] = $element->toArray();
            }
            
            // Broadcast to other users
            $this->dispatch('element-updated', element: $element->toArray());
        }
    }

    public function deleteElement($elementId)
    {
        $element = BoardElement::find($elementId);
        
        if ($element && $element->board_id === $this->boardId) {
            $element->delete();
            
            // Remove from local state
            $this->elements = collect($this->elements)
                ->reject(fn($e) => $e['id'] === $elementId)
                ->values()
                ->toArray();
            
            // Broadcast to other users
            $this->dispatch('element-deleted', elementId: $elementId);
        }
    }

    public function updateCursorPosition($x, $y)
    {
        BoardCollaborator::where('board_id', $this->boardId)
            ->where('user_id', Auth::id())
            ->update([
                'cursor_position' => ['x' => $x, 'y' => $y],
                'last_seen_at' => now(),
            ]);

        $this->dispatch('cursor-moved', [
            'userId' => Auth::id(),
            'x' => $x,
            'y' => $y,
        ]);
    }

    public function toggleShareModal()
    {
        $this->showShareModal = !$this->showShareModal;
    }

    public function togglePresentationMode()
    {
        $this->isPresentationMode = !$this->isPresentationMode;
    }

    public function updateBoardName($name)
    {
        $this->board->update(['name' => $name]);
    }

    public function saveCanvasData($jsonData)
    {
        try {
            $elements = json_decode($jsonData, true);
            
            // Delete existing elements for this board
            BoardElement::where('board_id', $this->boardId)->delete();
            
            // Save new elements
            foreach ($elements as $index => $element) {
                BoardElement::create([
                    'board_id' => $this->boardId,
                    'type' => $element['type'] ?? 'unknown',
                    'properties' => $element,
                    'z_index' => $index,
                    'created_by' => Auth::id(),
                ]);
            }
            
            $this->dispatch('canvas-saved');
            
        } catch (\Exception $e) {
            \Log::error('Canvas save error: ' . $e->getMessage());
        }
    }

    public function loadCanvasData()
    {
        $elements = BoardElement::where('board_id', $this->boardId)
            ->orderBy('z_index')
            ->get()
            ->map(function($element) {
                return $element->properties;
            })
            ->toArray();
            
        return $elements;
    }

    private function getNextZIndex()
    {
        return BoardElement::where('board_id', $this->boardId)->max('z_index') + 1;
    }

    private function refreshCollaborators()
    {
        $this->collaborators = BoardCollaborator::with('user')
            ->where('board_id', $this->boardId)
            ->where('last_seen_at', '>=', now()->subMinutes(5))
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.collaborative-board')->layout('layouts.app');
    }
}
