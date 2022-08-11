# Muban connector for PHP

An event subscriber that listens for requests to a given endpoint (default: `muban/view`),   
and returns the correct component.

## Requirements
- Composer 2
- PHP >= 8.1

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

// Inject the $dispatcher in your http kernel.
// Requests to muban/view will be captured and handled.
```

### Creating new components

Generate a component from raw json input:  
```bash
bin/console muban:from-json --raw
```

Generate a component from a json file:  
```bash
bin/console muban:from-json --file ./cl-a1-heading.json
```

## Component Library
There is a postman collection included in this repo.
Update the host and route variable if other than `localhost` and `muban/view`.
### Atoms

#### CL A1 Heading
Request:  
```json
{
  "component": "cl-a1-heading",
  "parameters": {
    "as": "h1",
    "copy": "The quick brown fox jumps over the lazy dog.",
    "ariaLabel": "a1-heading",
    "style": "heading-1",
    "className": "foo",
    "id": "bar"
  }
}
```

Response:
```html
<h1 data-component="cl-a1-heading" class="heading-1 foo" aria-label="a1-heading" id="bar">
    The quick brown fox jumps over the lazy dog.
</h1>
```
#### CL A2 Icon
Request:  
```json
{
  "component": "cl-a2-icon",
  "parameters": {
    "name": "arrow-right",
    "className": "foo",
    "id": "bar"
  }
}
```
Response:  
```html
<span data-component="cl-a2-icon" data-name="arrow-right" class="foo" id="bar"/>
```
#### CL A3 Image
Request:  
```json
{
    "component": "cl-a3-image",
    "parameters": {
        "src": "https://via.placeholder.com/640x480?text=Default+image",
        "alt": "The quick brown fox jumps over the lazy dog.",
        "sources": [
            {
                "srcset": "https://via.placeholder.com/1280x720?text=Large+image",
                "media": "(min-width:768px)"
            }
        ],
        "enableLazyLoading": true,
        "enableTransitionIn": true,
        "className": "foo",
        "objectFit": "none",
        "id": "bar"
    }
}
```
Response:  
```html
<picture data-component="cl-a3-image" id="bar" class="foo fit-none enable-transition-in">
	<source data-srcset="https://via.placeholder.com/1280x720?text=Large+image" media="(min-width:768px)" />
	<img data-src="https://via.placeholder.com/640x480?text=Default+image" alt="The quick brown fox jumps over the lazy dog."/>
</picture>
```
#### CL A5 Text

### Molecules

#### CL M1 Button
Request:
```json
{
    "component": "cl-m1-button",
    "parameters": {
        "label": "Click me!",
        "href": "https://github.com/mubanjs",
        "disabled": false,
        "target": "_self",
        "className": "foo",
        "icon": "arrow-left",
        "size": "medium",
        "id": "bar",
        "title": "foo",
        "ariaLabel": "bar",
        "ariaControls": "baz",
        "iconAlignment": "right"
    }
}
```
Response:
```html
<a data-component="cl-m1-button" data-label="Click me!" href="https://github.com/mubanjs" id="bar" title="foo"
	target="_self" rel="noopener" class="foo icon-alignment-right size-medium" aria-label="bar" aria-controls="baz">

	<span data-component="cl-a5-text" class="copy-1 button-label">Click me!</span>

	<span data-component="cl-a2-icon" data-name="arrow-left" class="button-icon"/>
</a>
```
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