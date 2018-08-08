<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class Index
{
  function index()
  {
    return new Response(
      '<html><body>Index Page</body></html>'
    );
  }
}