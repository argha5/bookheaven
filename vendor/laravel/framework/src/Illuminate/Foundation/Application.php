<?php

namespace Illuminate\Foundation;

class Application
{
    protected $basePath;
    protected $bindings = [];
    protected $instances = [];
    
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }
    }
    
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        return $this;
    }
    
    public function basePath($path = '')
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
    
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->bindings[$abstract] = compact('concrete', 'shared');
    }
    
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }
    
    public function make($abstract, array $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        if (isset($this->bindings[$abstract])) {
            $binding = $this->bindings[$abstract];
            $concrete = $binding['concrete'];
            
            if (is_callable($concrete)) {
                $object = $concrete($this, $parameters);
            } else {
                $object = new $concrete;
            }
            
            if ($binding['shared']) {
                $this->instances[$abstract] = $object;
            }
            
            return $object;
        }
        
        return new $abstract;
    }
    
    public function register($provider)
    {
        if (is_string($provider)) {
            $provider = new $provider($this);
        }
        
        if (method_exists($provider, 'register')) {
            $provider->register();
        }
        
        return $provider;
    }
}
