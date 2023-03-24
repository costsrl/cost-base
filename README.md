CostBase Base Mudule
=======

**What is CostBase?**

CostBase is a Module for Navigation based on Laminas Framework 2.4.x

**What exactly does CostBase?**

CostBase offes some common functionality

Installation
============
Create novigo folder under vendor directory 
Copy or Clone Module under novigo directory
Go to your application configuration in ```./config/application.config.php```and add 'CostBase'.
An example application configuration could look like the following:

'modules' => array(
    'Application',
    'CostBase'
)
```

open composer.json and add under auotload key

"autoload" : {
    "psr-0" : {
      "CostBase" : "vendor/cost/cost-base/src",
    }
```


add to main composer 

"repositories": [
        {
            "type": "vcs",
            "url": "http://git.cost.it/cost/cost-base.git"
        }
    ]