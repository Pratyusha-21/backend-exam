<?php
namespace Drupal\shopping\Plugin\Block;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "redirect_block",
 *   admin_label = @Translation("Shopping"),
 *   category = "custom"
 * )
 */
class RedirectBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Stores the current logged in user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface object
   */
  protected AccountProxyInterface $currentUser;
  /**
   * This method initializes the current logged in user and the current route.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the object of the AccountProxyInterface class - current user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected AccountProxyInterface $current_user,
    ) {
    $this->currentUser = $current_user;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }
  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
    );
  }
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\shopping\Form\BuyNowForm');
    return $form;
  }
}