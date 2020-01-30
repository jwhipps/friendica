<?php

namespace Friendica\Object;

use Friendica\Object\EMail\IEmail;

/**
 * The default implementation of the IEmail interface
 *
 * Provides the possibility to reuse the email instance with new recipients (@see Email::withRecipient())
 */
class Email implements IEmail
{
	/** @var string */
	private $fromName;
	/** @var string */
	private $fromAddress;
	/** @var string */
	private $replyTo;

	/** @var string */
	private $toAddress;

	/** @var string */
	private $subject;
	/** @var string */
	private $msgHtml;
	/** @var string */
	private $msgText;

	/** @var string */
	private $additionalMailHeader = '';
	/** @var int|null */
	private $toUid = null;

	public function __construct(string $fromName, string $fromAddress, string $replyTo, string $toAddress,
	                            string $subject, string $msgHtml, string $msgText,
	                            string $additionalMailHeader = '', int $toUid = null)
	{
		$this->fromName             = $fromName;
		$this->fromAddress          = $fromAddress;
		$this->replyTo              = $replyTo;
		$this->toAddress            = $toAddress;
		$this->subject              = $subject;
		$this->msgHtml              = $msgHtml;
		$this->msgText              = $msgText;
		$this->additionalMailHeader = $additionalMailHeader;
		$this->toUid                = $toUid;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFromName()
	{
		return $this->fromName;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFromAddress()
	{
		return $this->fromAddress;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getReplyTo()
	{
		return $this->replyTo;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getToAddress()
	{
		return $this->toAddress;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMessage(bool $plain = false)
	{
		if ($plain) {
			return $this->msgText;
		} else {
			return $this->msgHtml;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAdditionalMailHeader()
	{
		return $this->additionalMailHeader;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRecipientUid()
	{
		return $this->toUid;
	}

	/**
	 * Returns the current email with a new recipient
	 *
	 * @param string $email The email of the recipient
	 * @param int    $uid   The (optional) UID of the recipient for further infos
	 *
	 * @return static
	 */
	public function withRecipient(string $email, int $uid = null)
	{
		$newEmail            = clone $this;
		$newEmail->toAddress = $email;
		$newEmail->toUid     = $uid;

		return $newEmail;
	}

	/**
	 * Creates a new Email instance based on a given prototype
	 *
	 * @param static $prototype The base prototype
	 * @param array  $data      The delta-data (key must be an existing property)
	 *
	 * @return static The new email instance
	 */
	public static function createFromPrototype(Email $prototype, array $data = [])
	{
		$newMail = clone $prototype;

		foreach ($data as $key => $value) {
			if (property_exists($newMail, $key)) {
				$newMail->{$key} = $value;
			}
		}

		return $newMail;
	}
}