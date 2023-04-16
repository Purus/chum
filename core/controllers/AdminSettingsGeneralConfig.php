<?php
namespace Chum\Core\Controllers;

class AdminSettingsGeneralConfig
{
	protected string $siteEmail;
	protected string $siteTagline;
	protected string $siteUrl;

	/**
	 * Get the value of siteUrl
	 *
	 * @return string
	 */
	public function getSiteUrl(): string
	{
		return $this->siteUrl;
	}

	/**
	 * Set the value of siteUrl
	 *
	 * @param string $siteUrl
	 *
	 * @return self
	 */
	public function setSiteUrl(string $siteUrl): self
	{
		$this->siteUrl = $siteUrl;

		return $this;
	}

	/**
	 * Get the value of siteTagline
	 *
	 * @return string
	 */
	public function getSiteTagline(): string
	{
		return $this->siteTagline;
	}

	/**
	 * Set the value of siteTagline
	 *
	 * @param string $siteTagline
	 *
	 * @return self
	 */
	public function setSiteTagline(string $siteTagline): self
	{
		$this->siteTagline = $siteTagline;

		return $this;
	}

	/**
	 * Get the value of siteEmail
	 *
	 * @return string
	 */
	public function getSiteEmail(): string
	{
		return $this->siteEmail;
	}

	/**
	 * Set the value of siteEmail
	 *
	 * @param string $siteEmail
	 *
	 * @return self
	 */
	public function setSiteEmail(string $siteEmail): self
	{
		$this->siteEmail = $siteEmail;

		return $this;
	}
}