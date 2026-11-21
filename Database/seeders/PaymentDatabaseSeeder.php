<?php

namespace Modules\AdminManagement\Database\seeders;

use Illuminate\Database\Capsule\Manager as Capsule;

class PaymentDatabaseSeeder
{
    /**
     * Run the database seeds for payments.
     */
    public function run(): void
    {
        $clients = Capsule::table('clients')->get();
        $sellers = Capsule::table('sellers')->get();
        $orderItems = Capsule::table('order_items')->get();

        Capsule::table('payments')->truncate();

        $now = date('Y-m-d H:i:s');

        $samplePayments = [
            [
                'payment_type' => 'received',
                'amount' => 1140.00,
                'currency' => 'USD',
                'payment_method' => 'Bank Wire Transfer',
                'transaction_id' => 'WT-2026-98124',
                'payment_date' => date('Y-m-d', strtotime('-5 days')),
                'status' => 'completed',
                'client_index' => 0, // Acme Corp
                'vendor_index' => null,
                'order_item_index' => null,
                'notes' => 'Full upfront invoice payment for Order #ORD-2026-001.',
            ],
            [
                'payment_type' => 'paid',
                'amount' => 180.00,
                'currency' => 'USD',
                'payment_method' => 'Wise',
                'transaction_id' => 'WISE-TR-449102',
                'payment_date' => date('Y-m-d', strtotime('-4 days')),
                'status' => 'completed',
                'client_index' => null,
                'vendor_index' => 0, // Ahmed Hassan
                'order_item_index' => 0, // TechCrunch item
                'notes' => 'Payout for TechCrunch guest post publication.',
            ],
            [
                'payment_type' => 'paid',
                'amount' => 450.00,
                'currency' => 'USD',
                'payment_method' => 'Payoneer',
                'transaction_id' => 'PAY-882310-US',
                'payment_date' => date('Y-m-d', strtotime('-3 days')),
                'status' => 'completed',
                'client_index' => null,
                'vendor_index' => 0, // Ahmed Hassan
                'order_item_index' => 1, // Forbes item
                'notes' => 'Vendor fee for Forbes Enterprise Tech post.',
            ],
            [
                'payment_type' => 'received',
                'amount' => 500.00,
                'currency' => 'USD',
                'payment_method' => 'PayPal',
                'transaction_id' => 'PP-5590-ABCDE',
                'payment_date' => date('Y-m-d', strtotime('-2 days')),
                'status' => 'completed',
                'client_index' => 1, // Nexus Media
                'vendor_index' => null,
                'order_item_index' => 2,
                'notes' => 'Client deposit for Healthline & Search Engine Journal links.',
            ],
            [
                'payment_type' => 'paid',
                'amount' => 200.00,
                'currency' => 'USD',
                'payment_method' => 'Crypto (USDT-TRC20)',
                'transaction_id' => 'TX-USDT-908127394',
                'payment_date' => date('Y-m-d', strtotime('-1 day')),
                'status' => 'completed',
                'client_index' => null,
                'vendor_index' => 2, // Elena Rostova
                'order_item_index' => 5,
                'notes' => 'USDT payout for Investopedia publication.',
            ],
            [
                'payment_type' => 'received',
                'amount' => 650.00,
                'currency' => 'USD',
                'payment_method' => 'Stripe Credit Card',
                'transaction_id' => 'ch_3M4L4r2eZvKYlo2C1',
                'payment_date' => date('Y-m-d'),
                'status' => 'pending',
                'client_index' => 2, // Quantum Media
                'vendor_index' => null,
                'order_item_index' => 4,
                'notes' => 'Pending clearance on Stripe for Fintech guest post.',
            ],
        ];

        foreach ($samplePayments as $p) {
            $clientId = ($p['client_index'] !== null && isset($clients[$p['client_index']])) 
                ? $clients[$p['client_index']]->id 
                : null;
            $vendorId = ($p['vendor_index'] !== null && isset($sellers[$p['vendor_index']])) 
                ? $sellers[$p['vendor_index']]->id 
                : null;
            $orderItemId = ($p['order_item_index'] !== null && isset($orderItems[$p['order_item_index']])) 
                ? $orderItems[$p['order_item_index']]->id 
                : null;

            Capsule::table('payments')->insert([
                'payment_type' => $p['payment_type'],
                'amount' => $p['amount'],
                'currency' => $p['currency'],
                'payment_method' => $p['payment_method'],
                'transaction_id' => $p['transaction_id'],
                'payment_date' => $p['payment_date'],
                'status' => $p['status'],
                'client_id' => $clientId,
                'vendor_id' => $vendorId,
                'order_item_id' => $orderItemId,
                'notes' => $p['notes'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
