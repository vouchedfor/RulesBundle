# RulesBundle
This bundle creates a user interface for the audit log produced by [https://github.com/DATA-DOG/DataDogAuditBundle](https://github.com/DATA-DOG/DataDogAuditBundle).

It is based on the example provided by that bundle, but implemented in a more generic, out-of-the-box reusable way.

## Installation

First, install it with composer:

    composer require vouchedfor/audit-ui-bundle:dev-master

Then, add the following in your **AppKernel** bundles (note that 'new DataDog\AuditBundle\DataDogAuditBundle(),' may have been added previously).

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new DataDog\AuditBundle\DataDogAuditBundle(),	// Only add this if you haven't previously done so
            new DataDog\PagerBundle\DataDogPagerBundle(),
            new VouchedFor\AuditUiBundle\VouchedForAuditUiBundle(),
            ...
        );
        ...
    }

Add the name of the class responsible for managing users to `config.yml`. The example below assumes it's **AppBundle\Entity\User**:

    // app/config/config.yml
    vouched_for_audit_ui:
        user_class: AppBundle\Entity\User
        
Finally, add the routing (this assumes the route is prefixed with 'admin', that you'll probably want to protect behind a firewall in **security.yml**):

    // app/config/routing.yml
    vouchedfor_audit:
        resource: "@VouchedForAuditUiBundle/Controller/DefaultController.php"
        type:     annotation
        prefix:   /admin

## Usage

Navigate to **admin/audit** (assuming you've used the default routing above) to see the list of audited changes.

## Overriding templates
Templates (e.g. **diff.html.twig**) can be overridden at **app/Resources/VouchedForAuditUiBundle/views/Default**

## License

The Audit Ui Bundle is free to use and is licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php)