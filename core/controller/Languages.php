<?php
declare(strict_types=1);

/**
 * Gestor de idiomas con detección automática y persistencia en sesión.
 */
class Languages
{
	private string $defaultLanguage = 'es';
	private string $currentLanguage;
	private string $languagePath;
	private array $supportedLanguages = ['es', 'en', 'fr', 'de', 'pt', 'it'];

	public function __construct(?string $languagePath = null)
	{
		// Asegurar sesión iniciada
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$this->languagePath = $languagePath ?? dirname(__DIR__) . '/language/lang/';
		$this->currentLanguage = $this->detectLanguage();
		$this->loadLanguage();
	}

	/**
	 * Detecta el idioma a usar (prioridad: POST > GET > sesión > navegador > default).
	 */
	private function detectLanguage(): string
	{
		// 1. Cambio explícito por POST/GET
		$requested = $_POST['lang'] ?? $_GET['lang'] ?? null;
		if ($requested !== null && $this->isSupported($requested)) {
			$_SESSION['language'] = $requested;
			return $requested;
		}

		// 2. Idioma guardado en sesión
		if (isset($_SESSION['language']) && $this->isSupported($_SESSION['language'])) {
			return $_SESSION['language'];
		}

		// 3. Detectar del navegador
		$browserLang = $this->detectBrowserLanguage();
		if ($browserLang !== null) {
			$_SESSION['language'] = $browserLang;
			return $browserLang;
		}

		// 4. Default
		$_SESSION['language'] = $this->defaultLanguage;
		return $this->defaultLanguage;
	}

	/**
	 * Detecta el idioma preferido del navegador.
	 */
	private function detectBrowserLanguage(): ?string
	{
		if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			return null;
		}

		$acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		// Parsear formato: "es-ES,es;q=0.9,en;q=0.8"
		preg_match_all('/([a-z]{1,8})(-[a-z]{1,8})?\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLang, $matches);

		if (empty($matches[1])) {
			return null;
		}

		$langs = array_map('strtolower', $matches[1]);

		foreach ($langs as $lang) {
			if ($this->isSupported($lang)) {
				return $lang;
			}
		}

		return null;
	}

	/**
	 * Verifica si un idioma está soportado.
	 */
	private function isSupported(string $lang): bool
	{
		return in_array($lang, $this->supportedLanguages, true);
	}

	/**
	 * Carga el archivo de idioma correspondiente.
	 */
	private function loadLanguage(): void
	{
		$file = $this->languagePath . $this->currentLanguage . '.php';

		if (!file_exists($file)) {
			// Fallback al idioma por defecto
			$file = $this->languagePath . $this->defaultLanguage . '.php';
			if (!file_exists($file)) {
				throw new RuntimeException("Language file not found: {$file}");
			}
			$this->currentLanguage = $this->defaultLanguage;
		}

		require_once $file;
	}

	/**
	 * Obtiene el idioma actual.
	 */
	public function getCurrentLanguage(): string
	{
		return $this->currentLanguage;
	}

	/**
	 * Obtiene la lista de idiomas soportados.
	 */
	public function getSupportedLanguages(): array
	{
		return $this->supportedLanguages;
	}

	/**
	 * Cambia el idioma actual.
	 */
	public function setLanguage(string $lang): bool
	{
		if (!$this->isSupported($lang)) {
			return false;
		}

		$this->currentLanguage = $lang;
		$_SESSION['language'] = $lang;
		return true;
	}
}
