<?php

namespace App\Http\Controllers;

/**
 * Gives the application's HTTP controllers one shared base type.
 *
 * It intentionally adds no behavior, middleware, or dependencies. Each real
 * controller keeps its own request rules visible instead of inheriting hidden work.
 */
abstract class Controller {}
