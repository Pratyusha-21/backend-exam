<?php

namespace Drupal\products_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class is used to create Api for Product Node type.
 *
 * @package \Drupal\custom_api\Controller
 */
class ProductsNodeApiController extends ControllerBase {

  /**
   * Stores the instance of Entity Type Manager Interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Stores the instance of Account Proxy Interface.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Initializes the object to class variables.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Stores the instance of Entity Type Manager Interface.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the instance of Account Proxy Interface.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountProxyInterface $current_user) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
    );
  }

  /**
   * Generates the json response of Product nodes.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Stores the object of Request Class.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The data of the Product nodes.
   */
  public function generateApi(Request $request) {
    $json['title'] = 'Product Nodes';
    $json['data'] = [];

    /** @var \Drupal\node\Entity\Node $nodes */
    $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties(['type' => 'products']);
    
    // Getting the details of the product nodes.
    foreach ($nodes as $node) {
      $json['data'][] = [
        'product_uuid' => $node->uuid->value,
        'product_title' => $node->title->value,
        'created' => date('c', $node->created->value),
        'changed' => date('c', $node->changed->value),
        'field_image' => $node->field_image->value,
        'body' => [
          'value' => $node->body->value,
          'format' => $node->body->format,
          'processed' => $node->body->processed,
          'summary' => $node->body->summary,
        ],
      ];
    }
    return new JsonResponse($json);
  }
}
