<?php
session_start();

/**
 * Clase de carrito de compras basada en sesión.
 */
class Cart
{
    protected array $cart_contents = [];

    public function __construct()
    {
        $this->cart_contents = $_SESSION['cart_contents'] ?? null;
        if ($this->cart_contents === null) {
            $this->cart_contents = ['cart_total' => 0, 'total_items' => 0];
        }
    }

    /**
     * Retorna el contenido completo del carrito.
     */
    public function contents(): array
    {
        $cart = array_reverse($this->cart_contents);
        unset($cart['total_items'], $cart['cart_total']);
        return $cart;
    }

    /**
     * Obtiene un item específico del carrito.
     */
    public function get_item(string $row_id)
    {
        if (in_array($row_id, ['total_items', 'cart_total'], true)
            || !isset($this->cart_contents[$row_id])) {
            return false;
            }
            return $this->cart_contents[$row_id];
    }

    /**
     * Total de items en el carrito.
     */
    public function total_items(): int
    {
        return (int) $this->cart_contents['total_items'];
    }

    /**
     * Total del carrito.
     */
    public function total(): float
    {
        return (float) $this->cart_contents['cart_total'];
    }

    /**
     * Inserta un item en el carrito.
     */
    public function insert(array $item = [])
    {
        if (!is_array($item) || count($item) === 0) {
            return false;
        }

        if (!isset($item['id'], $item['name'], $item['price'], $item['qty'])) {
            return false;
        }

        $item['qty']   = (float) $item['qty'];
        $item['price'] = (float) $item['price'];

        if ($item['qty'] <= 0) {
            return false;
        }

        $rowid = md5((string) $item['id']);
        $old_qty = isset($this->cart_contents[$rowid]['qty'])
        ? (int) $this->cart_contents[$rowid]['qty']
        : 0;

        $item['rowid'] = $rowid;
        $item['qty']  += $old_qty;

        $this->cart_contents[$rowid] = $item;

        if ($this->save_cart()) {
            return $rowid;
        }
        return false;
    }

    /**
     * Actualiza un item del carrito.
     */
    public function update(array $item = []): bool
    {
        if (!is_array($item) || count($item) === 0) {
            return false;
        }

        if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])) {
            return false;
        }

        if (isset($item['qty'])) {
            $item['qty'] = (float) $item['qty'];
            if ($item['qty'] == 0) {
                unset($this->cart_contents[$item['rowid']]);
                return $this->save_cart();
            }
        }

        $keys = array_intersect(
            array_keys($this->cart_contents[$item['rowid']]),
                                array_keys($item)
        );

        if (isset($item['price'])) {
            $item['price'] = (float) $item['price'];
        }

        foreach (array_diff($keys, ['id', 'name']) as $key) {
            $this->cart_contents[$item['rowid']][$key] = $item[$key];
        }

        return $this->save_cart();
    }

    /**
     * Guarda el carrito en la sesión.
     */
    protected function save_cart(): bool
    {
        $this->cart_contents['total_items'] = 0;
        $this->cart_contents['cart_total']  = 0.0;

        foreach ($this->cart_contents as $key => $val) {
            if (!is_array($val) || !isset($val['price'], $val['qty'])) {
                continue;
            }
            $subtotal = $val['price'] * $val['qty'];
            $this->cart_contents['cart_total']  += $subtotal;
            $this->cart_contents['total_items'] += (int) $val['qty'];
            $this->cart_contents[$key]['subtotal'] = $subtotal;
        }

        if (count($this->cart_contents) <= 2) {
            unset($_SESSION['cart_contents']);
            return false;
        }

        $_SESSION['cart_contents'] = $this->cart_contents;
        return true;
    }

    /**
     * Elimina un item del carrito.
     */
    public function remove(string $row_id): bool
    {
        unset($this->cart_contents[$row_id]);
        return $this->save_cart();
    }

    /**
     * Vacía completamente el carrito.
     */
    public function destroy(): void
    {
        $this->cart_contents = ['cart_total' => 0, 'total_items' => 0];
        unset($_SESSION['cart_contents']);
    }
}
