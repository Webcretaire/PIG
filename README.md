# PIG

[![Latest Stable Version](https://poser.pugx.org/webcretaire/pig/v/stable)](https://packagist.org/packages/webcretaire/pig)

Simple ICS generator for PHP

### Usage
When creating a new calendar, you can optionally pass a TZID as a string to the constructor to make sure it will be correctly interpreted (this is highly recommended). The list of supported timezones can be found [here](https://github.com/Webcretaire/PIG/tree/master/timezones)
```php
$ics = new PIG\ICS('Europe/Paris'); // For example, or any timezone
```

Then you just have to put all your events using the ```addEvent``` function
```php
$ics->addEvent(
        '2018-10-06 20:15:00', // Start
        '2018-10-07 02:00:00', // End
        'Awesome party', // Title
        'At my house', // Optionnal location
        'Amazing party, with friends and all' // Optionnal description
    )->addEvent( // You can chain theese calls if you want
        new \DateTime('2018-10-07 15:00:42'), // Dates can be a \Datetime too
        new \DateTime('2018-10-07 02:00:00'),
        'House cleaning ...'
    );
```

Finally to write the file on disk, you need to call the ```saveICS``` function, providing the path you want to write to

```php
$ics->saveICS('path.ics');
```
