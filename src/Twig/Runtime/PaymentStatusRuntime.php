<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class PaymentStatusRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getStatusText(string $status): string
    {
        switch ($status) {
            case 'unpaid':
                return 'Payment is not successful!';
            case 'paid':
                return 'Payment successful!';
        }
    }
}
