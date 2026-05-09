use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    public function create() {
        return view('kasir.index', [
            'products' => Product::all(),
            'users' => User::all()
        ]);
    }

    public function history() {
        return view('customer.history', [
            'orders' => Order::where('user_id', auth()->id())->get()
        ]);
    }

    public function invoice($id) {
        $order = Order::with('items')->findOrFail($id);
        return view('invoice.show', compact('order'));
    }
}