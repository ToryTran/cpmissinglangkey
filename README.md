# cpmissinglangkey
Copy missing key => value between language files

# Problems

We have some lang files on project. But some key in language file is missing on another file. So we need insert/merge our file like that. 

vn/file1.php
```php
  <?php 
  return [
  'key_1' => 'value_1',
  'key_2' => 'value 2'
  ];
 ```

en/file1.php

```php
    
  <?php 
  
  return [
  'key_1' => 'value_1',
  'key_2' => 'value 2'
  'key_3' => 'value 3'
  ];
 ```
 
We need merge the key_3 of en/file1.php to vn/file1.php

after converting 

vn/file1.php
```php
  <?php 
  return [
  'key_1' => 'value_1',
  'key_2' => 'value 2'
  ];
 ```
 NEED-TRANS is pre_fix text to let we know that this key need translate again after merge to new file. 
 
# Required 
- Only supported PHP and the language file like
```php
    
  <?php 
  
  return [
  'key_1' => 'value_1',
  'key_2' => 'value 2'
  'key_3' => 'NEED-TRANS value 3'
  ];
 ```
 
- Lang folder 
<lang folder>
         en
              file 1
         vn 
              file 1

-------------------------

# Installation
Download the LangMapExtension.php file and run anywhere

# Usage

1 - Copy the LangMapExtension.php file to your project

2 - # php -f LangMapExtension.php path_to_lang_folder

example
tory@jpst[develop]$ php LangMapExtension.php  packages/mypackage/Main/resources/lang/

