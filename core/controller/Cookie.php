<?php
declare(strict_types=1);

/**
 * Gestor seguro de cookies con soporte para SameSite y atributos modernos.
 */
class Cookie
{
    private string $name = '';
    private string $value = '';
    private int $time;
    private string $path = '/';
    private string $domain = '';
    private bool $secure = false;
    private bool $httpOnly = true;
    private string $sameSite = 'Lax'; // Lax, Strict, None

    public function __construct()
    {
        $this->time = time() + 3600;

        // Detectar HTTPS automáticamente
        $this->secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
    }

    /**
     * Crea o actualiza la cookie.
     */
    public function create(): bool
    {
        if ($this->name === '') {
            throw new RuntimeException('Cookie name is required');
        }

        // PHP 7.3+ soporta array con opciones (incluye SameSite)
        if (PHP_VERSION_ID >= 70300) {
            return setcookie($this->name, $this->value, [
                'expires'  => $this->time,
                'path'     => $this->path,
                'domain'   => $this->domain,
                'secure'   => $this->secure,
                'httponly' => $this->httpOnly,
                'samesite' => $this->sameSite,
            ]);
        }

        // Fallback para PHP < 7.3
        $cookieValue = urlencode($this->name) . '=' . urlencode($this->value);
        $cookieValue .= '; expires=' . gmdate('D, d M Y H:i:s T', $this->time);
        $cookieValue .= '; path=' . $this->path;

        if ($this->domain !== '') {
            $cookieValue .= '; domain=' . $this->domain;
        }
        if ($this->secure) {
            $cookieValue .= '; secure';
        }
        if ($this->httpOnly) {
            $cookieValue .= '; httponly';
        }
        $cookieValue .= '; samesite=' . $this->sameSite;

        header('Set-Cookie: ' . $cookieValue, false);
        $_COOKIE[$this->name] = $this->value;
        return true;
    }

    /**
     * Obtiene el valor de la cookie.
     */
    public function get(): ?string
    {
        return $_COOKIE[$this->name] ?? null;
    }

    /**
     * Elimina la cookie.
     */
    public function delete(): bool
    {
        $this->value = '';
        $this->time  = time() - 3600;
        return $this->create();
    }

    // ========== SETTERS ==========

    public function setName(string $name): self
    {
        if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $name)) {
            throw new InvalidArgumentException('Invalid cookie name');
        }
        $this->name = $name;
        return $this;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Establece el tiempo de expiración.
     *
     * @param string $time Formato relativo (+1hour, +1day, etc) o timestamp Unix
     */
    public function setTime($time): self
    {
        if (is_numeric($time)) {
            $this->time = (int) $time;
        } else {
            $date = new DateTime();
            $date->modify((string) $time);
            $this->time = $date->getTimestamp();
        }
        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function setSecure(bool $secure): self
    {
        $this->secure = $secure;
        return $this;
    }

    public function setHttpOnly(bool $httpOnly): self
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    /**
     * Establece el atributo SameSite.
     *
     * @param string $sameSite Lax, Strict o None
     */
    public function setSameSite(string $sameSite): self
    {
        $valid = ['Lax', 'Strict', 'None'];
        if (!in_array($sameSite, $valid, true)) {
            throw new InvalidArgumentException(
                "SameSite must be one of: " . implode(', ', $valid)
            );
        }
        // Si es None, secure debe ser true
        if ($sameSite === 'None') {
            $this->secure = true;
        }
        $this->sameSite = $sameSite;
        return $this;
    }

    // ========== GETTERS ==========

    public function getName(): string { return $this->name; }
    public function getValue(): string { return $this->value; }
    public function getTime(): int { return $this->time; }
    public function getPath(): string { return $this->path; }
    public function getDomain(): string { return $this->domain; }
    public function getSecure(): bool { return $this->secure; }
    public function getHttpOnly(): bool { return $this->httpOnly; }
    public function getSameSite(): string { return $this->sameSite; }
}
