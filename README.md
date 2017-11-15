# V2 Documentation

To start using V2 Documentation it's necessary to bring all files together. For example, Hercule allows to do this in an easy way.
Hercule is a command-line tool and library for transcluding markdown, API Blueprint, and plaintext. 
This allows complex and repetitive documents to be written as smaller logical documents, for improved consistency, reuse, and separation of concerns.

## Creating new documentation
> Hercule is a command-line tool and library for transcluding markdown, API Blueprint, and plain text. 
This allows complex and repetitive documents to be written as smaller logical documents, for improved consistency, reuse, and separation of concerns.
### Install Hercule using npm:

[![Version](https://img.shields.io/npm/v/hercule.svg)](https://npmjs.com/package/hercule)
[![License](https://img.shields.io/npm/l/hercule.svg)](https://npmjs.com/package/hercule)
[![Build Status](https://img.shields.io/travis/jamesramsay/hercule/master.svg)](https://travis-ci.org/jamesramsay/hercule)
[![Coverage Status](https://img.shields.io/codecov/c/github/jamesramsay/hercule/master.svg)](https://codecov.io/github/jamesramsay/hercule)

<img src="https://cdn.rawgit.com/jamesramsay/hercule/16c858e8048830bd058ed632e59a988d67845029/hercule.svg" alt="Hercule" width="128px">

```sh 
$ npm install -g hercule
```

**Creating new documentation**

You can use Hercule as a command-line utility:

```sh 
$ cd docs
$ hercule apiblueprint.md -o ../apiary.apib
```
**`Notice:`** output file (apiary.apib) should be created on root level of the project.
 
## Testing-flow for documentation
After this **`apiary.apib`** is created and includes all endpoints collection, the testing process can be implemented by dredd. 
> Dredd is a language-agnostic command-line tool for validating
API description document against backend implementation of the API.
### Install DREDD using npm
[![npm version](https://badge.fury.io/js/dredd.svg)](https://www.npmjs.com/package/dredd)
[![Build Status](https://travis-ci.org/apiaryio/dredd.svg?branch=master)](https://travis-ci.org/apiaryio/dredd)
[![Build Status](https://ci.appveyor.com/api/projects/status/n3ixfxh72qushyr4/branch/master?svg=true)](https://ci.appveyor.com/project/Apiary/dredd/branch/master)
[![Coverage Status](https://coveralls.io/repos/apiaryio/dredd/badge.svg?branch=master)](https://coveralls.io/github/apiaryio/dredd)
[![Known Vulnerabilities](https://snyk.io/test/npm/dredd/badge.svg)](https://snyk.io/test/npm/dredd)

```sh 
$ npm install -g dredd
```

### Quick Start

1.  Create an file called **`apiary.apib`**.
    Follow the previous steps

2.  Create configuration file in any convenient way:

    2.1  Run interactive configuration:

    ```sh
    $ dredd init
    ```
    Add to the just created **`dredd.yml`** custom config data:
    
    ```sh
     custom:
       ApiClientId: ''
       ApiClientSecret: ''
       ApiUserEmail: ''
       ApiUserPassword: ''
       apiaryApiKey: ''
       apiaryApiName: ''
    ```

    2.2 Create **`dredd.yml`**  file from **`example-dredd.yml`** and adapt it to fit your environment.
    
3.  Run Dredd :

    ```sh
    $ dredd
    ```
  
  
**References:**
- https://github.com/jamesramsay/hercule
- https://github.com/apiaryio/dredd
