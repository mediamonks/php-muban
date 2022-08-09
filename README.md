# Muban connector for PHP


## Requirements
- php >= 8.1

## Installation

```bash 
composer req mediamonks/php-muban
```

## Usage
```php
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\ValidatorBuilder;
use MediaMonks\Muban\EventSubscriber\RequestSubscriber;
use MediaMonks\Muban\Component\Components;
use MediaMonks\Muban\Component\Library;

// Create required instances or fetch them from a container
$twig = new \Twig\Environment();
$validatorBuilder = new ValidatorBuilder();

// Load the components
$components = new Components([
    new Library\CLA1Heading(),
    new Library\CLA2Icon(),
    new Library\CLA3Image(),
    new Library\CLA5Text(),
    new Library\CLM1Button(),
    //...
]);

$eventSubscriber = new RequestSubscriber($twig, $components, $validatorBuilder->getValidator());
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber($eventSubscriber);

// inject the $dispatcher in your http kernel

```

## Component Library

### Atoms

#### CL A1 Heading
Request:  
```json
{
  "component": "cl-a1-heading",
  "parameters": {
    "as": "h1",
    "copy": "The quick brown fox jumps over the lazy dog.",
    "ariaLabel": "",
    "style": "heading-1",
    "className": "",
    "id": ""
  }
}
```

Response:
```html
<h1 data-component="cl-a1-heading" aria-label="aria-label" class="heading-1 awesome-header" id="awesome-header">
    The quick brown fox jumps over the lazy dog.
</h1>
```
#### CL A2 Icon

#### CL A3 Image

#### CL A5 Text

### Molecules

#### CL M1 Button

## Task list

### Atoms
- [x] CL A2 Icon
- [x] CL A3 Image
- [ ] CL A4 Rich Text
- [x] CL A5 Text
- [ ] CL A6 Divider
- [ ] CL A7 Video
- [ ] CL A8 Progress Bar

### Molecules
- [x] CL M1 Button
- [ ] CL M2 Input Field
- [ ] CL M3 Selection Control
- [ ] CL M4 Select
- [ ] CL M5 Title Wrapper
- [ ] CL M6 Accordion
- [ ] CL M8 Pagination
- [ ] CL M9 Tooltip
- [ ] CL M11 Range Slider
- [ ] CL M12 Rating
- [ ] CL M14 Link
- [ ] CL M15 Increment Stepper
- [ ] CL M16 Avatar
- [ ] CL M19 Icon Label
- [ ] CL M21 Pill
- [ ] CL M22 Date Input

### Organisms
- [ ] CL O1 Modal
- [ ] CL O2 Card
- [ ] CL O3 Tabs
- [ ] CL O5 Carousel
- [ ] CL O6 Video Player
- [ ] CL O7 Cookie Consent
- [ ] CL O8 Radio Group
- [ ] CL O9 File Select
- [ ] CL O10 Input List
- [ ] CL O14 Form Field