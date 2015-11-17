@TODO:
- add install info in this readme
- add composer.json with proper dependecies

The filtering bundle
====================

To use the PitechFiltersBundle, you must first add it to AppKernel:

.. code-block:: php

    new Pitech\FiltersBundle\PitechFiltersBundle(),

set the path to your bundle entities folder location, in **config.yml**:

.. code-block:: php

    pitech_filters.bundle_entities_folder: 'Pitech\MainBundle\Entity\'

if you want, you can customize the default delimiter by overriding them in app/config.yml:

.. code-block:: php

    parameters:
        pitech_filters.yaml_annotation_cache_dir: %kernel.root_dir%/cache/%kernel.environment%/annotations
        pitech_filters.filters_delimiter: ';'
        pitech_filters.expression_delimiter: '+'
        pitech_filters.interval_delimiter: ':'

The filtering service
_____________________

The filter service ``pitech_filters.filter.service`` offers a versatile approach to applying filtering
on any attribute of any entity, alongside a highly customizable annotation feature, used to specify
the aforementioned filters.

To specify that a filter corresponds to an entity attribute, you have to:

.. code-block:: php

    use Pitech\FiltersBundle\Annotations\FilterField

    /**
     * @var date
     * @FilterField()
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

In the above example, the $date attribute will be assigned the filter with the same name, if none
is specified. Although, you may want your filter to be named differently than the attribute, so
you can specify the desired name(and an optional 'options' array), like in the example below:

.. code-block:: php

    /**
     * @var date
     * @FilterField(name="created", options={})
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;

To use the service with an API you must follow a set of rules.
The format of the string containing filter details is:

.. code-block:: php

    [<F>=<E><E_D><D>]

or

.. code-block:: php

    [<F>=<E><E_D><D><F_D><E><E_D><D><I_D><D>]

where

.. code-block:: php

    F = filter,
    E = exception,
    D = delimiter,
    I = interval

- the filter query parameters are declared as an array:

.. code-block:: php

    ...&filters=[hours=gte%2B6;name=mike;task.name=%2Binstall;date=bw:2015-04-01:2015-04-10]

- if the element to filter by is in the entity you wish to use as base, you just specify
the attribute to filter by(%2B stands for +):

.. code-block:: php

    ...&filters=[name=like%2BAnd]

or if it lays in a related entity, you specify the path to the attributes' entity:

.. code-block:: php

    ...&filters=[task.project.name=like%2BDotmanager]

API call usage - filter types
_____________________________

By default there are 5 filter types predefined, each of them with its own set of expressions:

Numeric filter:
###############

-greater than, lower than, greater than or equal, lower than or equal;
    ['gt', 'lt', 'gte', 'lte']
    e.g. check if hours attribute is greater than:

.. code-block:: php

    &filters=[hours=gt%2B5]

If no expression is specified, it defaults to "=":

.. code-block:: php

    &filters=[hours=6]

String filter:
##############

-check for a substring with LIKE;
    ['like']
    e.g. check attribute by given string:

.. code-block:: php

    &filters=[name=like%2Band]

If no expression is specified, it defaults to strict equal:

.. code-block:: php

    &filters=[name=Andrei]

Date filter:
############

-greater than, lower than, greater than or equal, lower than or equal;
    ['gt', 'lt', 'gte', 'lte']
    e.g. check if date is lower than:

.. code-block:: php

    &filters=[date=lt%22015-05-05]

If no expression is specified, it defaults to strict equal:

.. code-block:: php

    &filters=[date=2015-05-05]

Numeric interval filter:
########################

-greater than, lower than, greater than or equal, lower than or equal;
    ['bw']
    e.g. check if hours attribute lies between 4 and 8:

.. code-block:: php

    &filters=[hours=bw%2B4:8]

If no expression is specified, it defaults to strict equal:

.. code-block:: php

    &filters=[date=2015-05-05]

Date interval filter:
#####################

-greater than, lower than, greater than or equal, lower than or equal;
    ['bw']
        e.g. check if date attribute lies between two given dates:

.. code-block:: php

    &filters=[date=bw%2B2015-04-01:2015-04-10]

If no expression is specified, it defaults to strict equal:

.. code-block:: php

    &filters=[date=2015-05-05]

Check filter:
#####################

-is Null or is Not Null;
    ['is']
        e.g. check if a field has the value null or not:

Is null check:
.. code-block:: php

    &filters=[validated=is+Null]

Is not null check:
.. code-block:: php

    &filters=[validated=is+NotNull]

Backend usage
_____________

The usage of the filtering service requires is as easy as it looks:

.. code-block:: php

    if ($filters = $parameterBag->get('filters')) {
        $qb = $this->filterService->filter($filters, $qb, $repositoryClass, $alias);
    }

In this fine example the Parameter bag is used to provide the filter string, but you can also
provide it by using other means that suit your needs, just keep in mind to respect the format:

.. code-block:: php

    [<filter_name>=<value>]

for the default expression, or

.. code-block:: php

    [<filter_name>=<expression><expression_delimiter><value>]

and for chaining filters:

.. code-block:: php

    [<filter_name>=<expression><expression_delimiter><value><filter_delimiter><expression><expression_delimiter><value>]

You need a predefined Query Builder object, the main repository class on which the filters should
be applied, or from where the joins to other entities should be made, and an alias to distinguish
the query table names.