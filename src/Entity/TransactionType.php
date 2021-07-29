<?php

namespace Drupal\transaction_module\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Transaction type entity.
 *
 * @ConfigEntityType(
 *   id = "transaction_type",
 *   label = @Translation("Transaction type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\transaction_module\TransactionTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\transaction_module\Form\TransactionTypeForm",
 *       "edit" = "Drupal\transaction_module\Form\TransactionTypeForm",
 *       "delete" = "Drupal\transaction_module\Form\TransactionTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\transaction_module\TransactionTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "transaction_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "transaction",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/transaction_type/{transaction_type}",
 *     "add-form" = "/admin/structure/transaction_type/add",
 *     "edit-form" = "/admin/structure/transaction_type/{transaction_type}/edit",
 *     "delete-form" = "/admin/structure/transaction_type/{transaction_type}/delete",
 *     "collection" = "/admin/structure/transaction_type"
 *   }
 * )
 */
class TransactionType extends ConfigEntityBundleBase implements TransactionTypeInterface {

  /**
   * The Transaction type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Transaction type label.
   *
   * @var string
   */
  protected $label;
  protected $description;


  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

}
