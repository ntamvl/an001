<?php
/**
 * CleanFix database preferences branch model
 *
 * @class           WPXCleanFixPreferencesDatabase
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-23
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferencesDatabase extends WPDKPreferencesBranch {

  const IGNORE_INNODB        = 'ignore_innodb';
  const RESET_AUTO_INCREMENT = 'reset_auto_increment';

  /**
   * Ignore InnoDB during the list and optimization process
   *
   * @brief Ignore InnoDB
   *
   * @var bool $ignoreInnoDB
   */
  public $ignoreInnoDB;

  /**
   * After optimize reset the AUTO_INCREMENT index table
   *
   * @brief Reset AUTO_INCREMENT
   *
   * @var bool $resetAutoIncrement
   */
  public $resetAutoIncrement;

  /**
   * Set the default configuration
   *
   * @brief Default configuration
   */
  public function defaults()
  {
    $this->ignoreInnoDB       = false;
    $this->resetAutoIncrement = true;
  }

  /**
   * Update this branch
   *
   * @brief Update
   */
  public function update()
  {
    $this->ignoreInnoDB       = isset( $_POST[self::IGNORE_INNODB] ) ? $_POST[self::IGNORE_INNODB] : 'off';
    $this->resetAutoIncrement = isset( $_POST[self::RESET_AUTO_INCREMENT] ) ? $_POST[self::RESET_AUTO_INCREMENT] : 'on';
  }

}
