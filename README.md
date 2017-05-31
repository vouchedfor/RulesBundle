# RulesBundle
This bundle creates the facility to add a basic rules engine.  It's inspired by this article: [http://jwage.com/post/76799775984/using-the-symfony-expression-language-for-a-reward](http://jwage.com/post/76799775984/using-the-symfony-expression-language-for-a-reward)

The bundle creates a Rule entity which is comprised of three elements:

    1. Event - the dispatched event that causes this rule to be invoked (e.g. 'user:registered')
    2. Conditions - a json array of expressions. (e.g. '["data.age > 67", "data.weight > 200"]') See [http://symfony.com/doc/current/components/expression_language.html](http://symfony.com/doc/current/components/expression_language.html) for more on the ExpressionLanguage component.
    3. Actions - a json array of events to be dispatched. (e.g. '["user:mark-as-retired", "user:send-email|Retirement"]'.  Note that it is possible to send a single parameter by adding a '|' after the name of the action/event to be dispatched)


## Installation

First, install it with composer:

    composer require vouchedfor/rules-bundle:dev-master

Then, add the following in your **AppKernel** bundles (note that 'new DataDog\AuditBundle\DataDogAuditBundle(),' may have been added previously).

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new VouchedFor\AuditUiBundle\VouchedForRulesBundle(),
            ...
        );
        ...
    }

Create the 'rule' database table used by the bundle:

Using [Doctrine Migrations Bundle](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html):

    php app/console doctrine:migrations:diff
    php app/console doctrine:migrations:migrate
    
Using Doctrine Schema:
    
    php app/console doctrine:schema:update --force
    
## Usage
Create a class that extends VouchedFor\RulesBundle\Event\RuleEvent.

Add the events you want to listen to as tags for a RuleListener service (in this example, 'user:mark-as-retired' and 'user:send-email'):

    // app/config/services.yml
    app.rule.listener:
        class: VouchedFor\RulesBundle\EventListener\RuleListener
        arguments:
          - "@doctrine.orm.entity_manager"
          - "@event_dispatcher"
        tags:
          - { name: kernel.event_listener, event: user:mark-as-retired, method: handleEvent }
          - { name: kernel.event_listener, event: user:send-email, method: handleEvent }



Add a listener/subscriber to listen to the dispatched actions when a rule passes. For example:

    <?php
    namespace AppBundle\EventListener;
    
    ...
    
    class UserSubscriber implements EventSubscriberInterface
    {
        public static function getSubscribedEvents()
        {
            return [
                'user:mark-as-retired' => 'userMarkAsRetired',
                'user:send-email' => 'userSendEmail',
                ];
        }
        
        public function userMarkAsRequired(UserEvent $event)
        {
            $entity = $event->getEntity());
               $entity->setStatus('retired');
            
            ...
        }
        
        public function userSendEmail(UserEvent $event)
        {
            $entity = $event->getEntity());
            $parameter = $event->getActionParameter());
            
            $this->sendMail($entity, $parameter);  // Send email to $entity with template $parameter
            
            ...
        }

## License

The Rules Bundle is free to use and is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php)