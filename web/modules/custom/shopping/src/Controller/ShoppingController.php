<?php
namespace Drupal\shopping\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * This class will help to show thank you page with product and user details.
 */
class ShoppingController extends ControllerBase {
  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return (
      $container->get('file_system')
    );
  }
  /**
   * This method creates the thank you page with product details.
   *
   * @return array
   *   The render array for the page.
   */
  public function thankYouDetails(Request $request) {
    $nid = $request->query->get('nid');
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    if ($node && $node->getType() === 'products') {
      $product_title = $node->getTitle();
      $message = $this->t('Thank You, You have Purchased: @title', [
        '@title' => $product_title,
      ]);
      return [
        'message' => [
          '#markup' => $message,
        ],
      ];
    }
    return [
      '#markup' => $this->t('Sorry,your request cannot be processed try again after some time.'),
    ];
  }
}