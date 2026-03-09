Nullable ? on optional fieldsMatches nullable DB columns — no undefined index errors

(bool) cast on is_active / is_availableMySQL returns 0/1 as strings — cast makes it a true PHP bool

(float) cast on pricesPDO returns all values as strings by default

(int) cast on quantitySame reason — explicit typing

Joined fields on Order and OrderItemViews need user_name, room_number etc. — carried on the model when JOINed, null otherwise

isAdmin() on UserConvenience method used by controllers and session checks

isCancellable() on OrderEncapsulates status logic in the model, not scattered in views

subtotal() on OrderItemSingle place to calculate line total — quantity × unit_price

$items[] array on OrderOrders carry their items after being hydrated by the repository