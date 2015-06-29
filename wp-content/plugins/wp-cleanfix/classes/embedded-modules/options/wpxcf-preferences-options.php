<?php
/**
 * CleanFix options preferences branch model
 *
 * @class           WPXCleanFixPreferencesOptions
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-23
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferencesOptions extends WPDKPreferencesBranch {

  const EXPIRY_DATE = 'expiry_date';
  const SAFE_MODE   = 'safe_mode';

  public $expiryDate;
  public $safeMode;

  /**
   * Set the default configuration
   *
   * @brief Default configuration
   */
  public function defaults()
  {
    $this->expiryDate = 0;
    $this->safeMode   = true;
  }

  /**
   * Update this branch
   *
   * @brief Update
   */
  public function update()
  {
    $this->expiryDate = $_POST[self::EXPIRY_DATE];
    $this->safeMode   = isset( $_POST[self::SAFE_MODE] ) ? $_POST[self::SAFE_MODE] : 'on';
  }

}