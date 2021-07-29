<?php

namespace Drupal\transaction_module\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Transaction entity.
 *
 * @ingroup transaction_module
 *
 * @ContentEntityType(
 *   id = "transaction",
 *   label = @Translation("Transaction"),
 *   bundle_label = @Translation("Transaction type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\transaction_module\TransactionListBuilder",
 *     "views_data" = "Drupal\transaction_module\Entity\TransactionViewsData",
 *     "translation" = "Drupal\transaction_module\TransactionTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\transaction_module\Form\TransactionForm",
 *       "add" = "Drupal\transaction_module\Form\TransactionForm",
 *       "edit" = "Drupal\transaction_module\Form\TransactionForm",
 *       "delete" = "Drupal\transaction_module\Form\TransactionDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\transaction_module\TransactionHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\transaction_module\TransactionAccessControlHandler",
 *   },
 *   base_table = "transaction",
 *   data_table = "transaction_field_data",
 *   translatable = TRUE,
 *   permission_granularity = "bundle",
 *   admin_permission = "administer transaction entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/transaction/{transaction}",
 *     "add-page" = "/admin/structure/transaction/add",
 *     "add-form" = "/admin/structure/transaction/add/{transaction_type}",
 *     "edit-form" = "/admin/structure/transaction/{transaction}/edit",
 *     "delete-form" = "/admin/structure/transaction/{transaction}/delete",
 *     "collection" = "/admin/structure/transaction",
 *   },
 *   bundle_entity_type = "transaction_type",
 *   field_ui_base_route = "entity.transaction_type.edit_form"
 * )
 */
class Transaction extends ContentEntityBase implements TransactionInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Transaction entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Transaction entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Transaction is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['sender'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Sender"))
      ->setDisplayOptions('form', ["type" => "string_textfield"])
      ->setRequired(TRUE);

    $fields['recipient'] = BaseFieldDefinition::create('string')
      ->setLabel('Recipient')
      ->setDisplayOptions('form', ["type" => "string_textfield"])
      ->setRequired(TRUE);

    $fields['amount'] = BaseFieldDefinition::create('decimal')
      ->setLabel('Amount')
      ->setDisplayOptions('form', ["type" => "number"])
      ->setRequired(TRUE);
    return $fields;
  }

}
