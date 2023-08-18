<?php
namespace Drupal\shopping\Form;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Defines a generic form which contains add to cart and buy now buttons.
 */
class BuyNowForm extends FormBase {
  /**
   * Stores the current route.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch object
   */
  protected CurrentRouteMatch $currentRoute;
  /**
   * Stores the current logged in user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface object
   */
  protected AccountProxyInterface $currentUser;
  /**
   * Initializes the \Drupal\Core\Ajax\AjaxResponse instance.
   *
   * @var \Drupal\Core\Ajax\AjaxResponse
   */
  protected $response;
  /**
   * This method initializes the current logged in user and the current route.
   *
   * Storing the AjaxResponse instance to the class variable.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the object of the AccountProxyInterface class - current user.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route_match
   *   Stores the object of the RouteMatch class - current route.
   */
  public function __construct(protected AccountProxyInterface $current_user, protected CurrentRouteMatch $route_match) {
    $this->currentUser = $current_user;
    $this->currentRoute = $route_match;
    $this->response = new AjaxResponse();
  }
  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('current_route_match'),
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'shopping_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node = NULL) {
    $form['add_items_to_cart'] = [
      '#type' => 'button',
      '#value' => $this->t('Add to Cart'),
      '#suffix' => '<div id="add-items-to-cart"></div>',
      '#ajax' => [
        'callback' => '::addItemsCallback',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Product Adding To Cart'),
        ],
      ],
    ];
    $form['buy_now'] = [
      '#type' => 'submit',
      '#value' => $this->t('Buy Now'),
      '#submit' => ['::buyNowSubmitProducts'],
    ];
    return $form;
  }
  /**
   * Displays message using AJAX when user clicks on 'Add To Cart' button.
   *
   * @param array $form
   *   Stores the data in the form fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Stores the object of FormStateInterface.
   */
  public function addItemsCallback(array &$form, FormStateInterface $form_state) {
    $this->response->addCommand(new HtmlCommand('#add-to-cart', $this->t('Product is added to your cart')));
    $this->response->addCommand(new CssCommand('#add-to-cart', ['color' => '#000080']));
    return $this->response;
  }
  /**
   * Redirects to 'Thank You' page when user clicks on 'Buy Now' button.
   *
   * @param array $form
   *   Stores the data in the form fields.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Stores the object of FormStateInterface.
   */
  public function buyNowSubmitProducts(array &$form, FormStateInterface $form_state) {
    $node = $this->currentRoute->getParameter('node');
    if ($node instanceof Node && $node->getType() === 'products') {
      $product_nid = $node->id();
      $form_state->setRedirect('thank.you', ['nid' => $product_nid]);
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}