<?php

namespace Drupal\durhamatletico_registration\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\durhamatletico_registration\BulkImport;

/**
 * Implements an example form.
 */
class BulkUserRegistrationForm extends ConfirmFormBase {

  protected $data;
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bulk_user_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    // TODO: Present a summary: number of users, number of registrations.
    // TODO: Present detail, each row contains name, team and division.
    return $this->t('Do you want to import the following data %id?', array('%id' => $this->data));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('bulk_user_registration.form');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Running the importer is not reversible.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Proceed with import');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['csv'] = [
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#upload_location' => 'private://csv-registrations/',
      '#title' => $this->t('CSV'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $bulk_import = new BulkImport();
    $validators = ['file_validate_extensions' => ['csv']];
    $file = file_save_upload('csv', $validators, FALSE, 0, FILE_EXISTS_REPLACE);
    $file_uri = $file->getFileUri();
    $data = file_get_contents($file_uri);
    if (!$bulk_import->validateCsv($data)) {
      $form_state->setErrorByName('csv', $this->t('CSV did not pass validation.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Call registration import service.

    drupal_set_message($this->t('hi'));
  }

}