<?php

namespace Modules\AdminManagement\Database\seeders;

use Illuminate\Database\Capsule\Manager as Capsule;

class ExpenseDatabaseSeeder
{
    /**
     * Run the database seeds for expenses.
     */
    public function run(): void
    {
        Capsule::table('expenses')->truncate();

        $now = date('Y-m-d H:i:s');

        $sampleExpenses = [
            [
                'title' => 'Ahrefs Standard Agency Plan',
                'category' => 'Tools & Software',
                'amount' => 199.00,
                'currency' => 'USD',
                'expense_date' => date('Y-m-d', strtotime('-15 days')),
                'payment_method' => 'Credit Card',
                'description' => 'Monthly subscription for checking DR, backlink profiles & organic keyword traffic.',
            ],
            [
                'title' => 'Freelance Article Writing (5 Tech Articles)',
                'category' => 'Content Writing',
                'amount' => 125.00,
                'currency' => 'USD',
                'expense_date' => date('Y-m-d', strtotime('-10 days')),
                'payment_method' => 'PayPal',
                'description' => 'Native English copywriters for Forbes and TechCrunch guest posts (1000+ words each).',
            ],
            [
                'title' => 'Grammarly Premium Business Seat',
                'category' => 'Tools & Software',
                'amount' => 30.00,
                'currency' => 'USD',
                'expense_date' => date('Y-m-d', strtotime('-8 days')),
                'payment_method' => 'Debit Card',
                'description' => 'Automated proofreading, grammar & plagiarism checking for client content.',
            ],
            [
                'title' => 'Cloud VPS Hosting & Email Server',
                'category' => 'Hosting & Domains',
                'amount' => 45.00,
                'currency' => 'USD',
                'expense_date' => date('Y-m-d', strtotime('-5 days')),
                'payment_method' => 'Credit Card',
                'description' => 'DigitalOcean Droplet for cold email outreach and agency CRM.',
            ],
            [
                'title' => 'International Bank Wire & Currency Conversion Fees',
                'category' => 'Banking & Fees',
                'amount' => 28.50,
                'currency' => 'USD',
                'expense_date' => date('Y-m-d', strtotime('-2 days')),
                'payment_method' => 'Bank Transfer',
                'description' => 'Transaction fee deducted for receiving buyer wire transfer.',
            ],
            [
                'title' => 'LinkedIn Outreach Automation Tool',
                'category' => 'Marketing & Outreach',
                'amount' => 59.00,
                'currency' => 'USD',
                'expense_date' => date('Y-m-d', strtotime('-1 day')),
                'payment_method' => 'Credit Card',
                'description' => 'Monthly license for finding new SaaS link-building clients.',
            ],
        ];

        foreach ($sampleExpenses as $expense) {
            Capsule::table('expenses')->insert([
                'title' => $expense['title'],
                'category' => $expense['category'],
                'amount' => $expense['amount'],
                'currency' => $expense['currency'],
                'expense_date' => $expense['expense_date'],
                'payment_method' => $expense['payment_method'],
                'description' => $expense['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
