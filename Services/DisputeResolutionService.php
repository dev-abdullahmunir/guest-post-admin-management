<?php

namespace Modules\AdminManagement\Services;

use Modules\Shared\Services\BaseService;
use Modules\Shared\Enums\OrderStatus;

class DisputeResolutionService extends BaseService
{
    /**
     * Resolve a dispute in favor of Client (Refund) or Seller (Release Payout)
     */
    public function resolve(int $disputeId, string $decision, string $adminNotes)
    {
        $status = ($decision === 'refund_client') 
            ? OrderStatus::CANCELLED 
            : OrderStatus::COMPLETED;

        return $this->success([
            'dispute_id' => $disputeId,
            'decision' => $decision,
            'new_order_status' => $status,
            'notes' => $adminNotes,
        ], 'Dispute resolved successfully.');
    }
}
