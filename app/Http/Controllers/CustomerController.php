<?php

// App\Http\Controllers\CustomerController.php

namespace App\Http\Controllers;

use App\Models\Equipments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        // Fetching the customer's equipment based on their user ID
        $equipments = Equipments::where('customer_id', Auth::id())->get();

        return view('customer.dashboard', compact('equipments'));
    }

    // Additional methods can be added here, if needed
}
