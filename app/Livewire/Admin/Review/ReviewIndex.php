<?php

namespace App\Livewire\Admin\Review;

use App\Models\ProductReview;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Flux\Flux;

#[Layout('components.layouts.admin-app')]
class ReviewIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $ratingFilter = '';
    public $statusFilter = '';
    public $spamFilter = '0'; // Default hide spam
    public $selectedReviewId;

    protected $queryString = [
        'search' => ['except' => ''],
        'ratingFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'spamFilter' => ['except' => '0'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingRatingFilter() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingSpamFilter() { $this->resetPage(); }

    public function approve($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update([
            'is_approved' => 1,
            'is_spam' => 0
        ]);
        $review->product->syncRatings();
        $this->dispatch('notify', message: 'Review approved successfully!');
    }

    public function reject($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => 0]);
        $review->product->syncRatings();
        $this->dispatch('notify', message: 'Review rejected successfully!');
    }

    public function markAsSpam($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update([
            'is_spam' => 1,
            'is_approved' => 0,
            'is_featured' => 0
        ]);
        $review->product->syncRatings();
        $this->dispatch('notify', message: 'Review marked as spam!');
    }

    public function toggleFeatured($id)
    {
        $review = ProductReview::findOrFail($id);
        if ($review->is_spam) {
            $this->dispatch('notify', message: 'Cannot feature a spam review!', type: 'error');
            return;
        }
        $review->update(['is_featured' => !$review->is_featured]);
        $this->dispatch('notify', message: 'Review featured status updated!');
    }

    public function confirmDelete($id)
    {
        $this->selectedReviewId = $id;
        Flux::modal('delete-review-modal')->show();
    }

    public function delete()
    {
        $review = ProductReview::findOrFail($this->selectedReviewId);
        $product = $review->product;
        $review->delete();
        $product->syncRatings();
        
        Flux::modal('delete-review-modal')->close();
        $this->dispatch('notify', message: 'Review deleted successfully!');
    }

    public function render()
    {
        $reviews = ProductReview::with(['product', 'user'])
            ->when($this->search, function($q) {
                $q->where(function($sq) {
                    $sq->whereHas('product', function($pq) {
                        $pq->where('product_name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('user', function($uq) {
                        $uq->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('review', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->ratingFilter, function($q) {
                $q->where('rating', $this->ratingFilter);
            })
            ->when($this->statusFilter !== '', function($q) {
                $q->where('is_approved', $this->statusFilter);
            })
            ->where('is_spam', $this->spamFilter)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.review.review-index', [
            'reviews' => $reviews
        ]);
    }
}
