<?php

namespace Drupal\heartbeat\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\heartbeat\Entity;

/**
 * Form controller for Heartbeat edit forms.
 *
 * @ingroup heartbeat
 */
class HeartbeatForm extends ContentEntityForm {
//TODO add dependency injection
  protected $nodeManager;
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $this->nodeManager = \Drupal::service('entity_type.manager')->getStorage('node');
    /* @var $entity \Drupal\heartbeat\Entity\Heartbeat */
    $form = parent::buildForm($form, $form_state);
    $entity = &$this->entity;
    if ($entity->isNew()) {
      $form['new_revision'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 10,
      );
    }

    $form['uid'] = array(
      '#type' => 'entity_autocomplete',
      '#target_type' => 'user',
      '#default_value' => $entity->getOwner()->isAnonymous() ? NULL : $entity->getOwner(),
      // A comment can be made anonymous by leaving this field empty therefore
      // there is no need to list them in the autocomplete.
      '#selection_settings' => ['include_anonymous' => FALSE],
      '#title' => $this->t('Authored by'),
      '#description' => $this->t('No cucks.')
    );

    $form['message'] = array(
      '#type' => 'textarea',
      '#description' => t('The Heartbeat message'),
      '#title' => 'Message',
      '#default' => $entity->getMessage()->getValue()[0]['value'],
      '#value' => $entity->getMessage()->getValue()[0]['value'],
    );

    $nodeId = $entity->getNid()->getValue()[0]['target_id'];
    $node = $this->nodeManager->load($nodeId);

    $form['nid'] = array(
      '#type' => 'entity_autocomplete',
      '#entity_type' => 'node',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#default_value' => $nodeId > 0 ? null : $this->nodeManager->load($nodeId),
      '#title' => 'Node',
      '#description' => t('The node referenced by this Heartbeat')
    );

    $form['status'] = array(
      '#type' => 'checkbox',
      '#title' => 'Status',
      '#description' => t('Published'),
      '#default_value' => $entity->isPublished(),
    );

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime(REQUEST_TIME);
      $entity->setRevisionUserId(\Drupal::currentUser()->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Heartbeat.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Heartbeat.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.heartbeat.canonical', ['heartbeat' => $entity->id()]);
  }

}
