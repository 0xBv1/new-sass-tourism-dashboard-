@extends('layouts.dashboard.app')

@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <x-dashboard.partials.breadcrumb title="API Documentation">
            <li class="breadcrumb-item active">API Documentation</li>
        </x-dashboard.partials.breadcrumb>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>API Documentation</h5>
                            <p class="text-muted mb-0">Complete guide to using the Tourism API with Angular</p>
                        </div>
                        <div class="card-body">
                            <!-- Base URL Section -->
                            <div class="mb-5">
                                <h4 class="mb-3">Base URL</h4>
                                <div class="alert alert-info">
                                    <code>{{ $apiBaseUrl }}</code>
                                </div>
                                <p>All API endpoints are prefixed with this base URL. The API supports localization through the <code>Accept-Language</code> header.</p>
                            </div>

                            <!-- Authentication Section -->
                            <div class="mb-5">
                                <h4 class="mb-3">Authentication</h4>
                                <p>Most endpoints require authentication using Bearer tokens. After login, include the token in the Authorization header:</p>
                                <pre class="bg-light p-3 rounded"><code>Authorization: Bearer {your_token_here}</code></pre>
                                
                                <h5 class="mt-4 mb-3">Angular Service Example</h5>
                                <pre class="bg-light p-3 rounded"><code>// api.service.ts
import { Injectable } from '@@angular/core';
import { HttpClient, HttpHeaders } from '@@angular/common/http';
import { Observable } from 'rxjs';

@@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private baseUrl = '{{ $apiBaseUrl }}';
  
  constructor(private http: HttpClient) {}

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('auth_token');
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept-Language': 'en', // or 'ar', 'fr', etc.
      'Authorization': token ? `Bearer ${token}` : ''
    });
  }

  // Generic GET method
  get&lt;T&gt;(endpoint: string): Observable&lt;T&gt; {
    return this.http.get&lt;T&gt;(`${this.baseUrl}/${endpoint}`, {
      headers: this.getHeaders()
    });
  }

  // Generic POST method
  post&lt;T&gt;(endpoint: string, data: any): Observable&lt;T&gt; {
    return this.http.post&lt;T&gt;(`${this.baseUrl}/${endpoint}`, data, {
      headers: this.getHeaders()
    });
  }

  // Generic PUT method
  put&lt;T&gt;(endpoint: string, data: any): Observable&lt;T&gt; {
    return this.http.put&lt;T&gt;(`${this.baseUrl}/${endpoint}`, data, {
      headers: this.getHeaders()
    });
  }

  // Generic DELETE method
  delete&lt;T&gt;(endpoint: string): Observable&lt;T&gt; {
    return this.http.delete&lt;T&gt;(`${this.baseUrl}/${endpoint}`, {
      headers: this.getHeaders()
    });
  }
}</code></pre>
                            </div>

                            <!-- Authentication Endpoints -->
                            <div class="mb-5">
                                <h4 class="mb-3">Authentication Endpoints</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">POST /api/auth/login</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Authenticate user and receive access token</p>
                                        <p><strong>Request Body:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>{
  "email": "user@example.com",
  "password": "password123"
}</code></pre>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// auth.service.ts
import { Injectable } from '@@angular/core';
import { ApiService } from './api.service';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';

@@Injectable({
  providedIn: 'root'
})
export class AuthService {
  constructor(private api: ApiService) {}

  login(email: string, password: string): Observable&lt;any&gt; {
    return this.api.post('auth/login', { email, password }).pipe(
      tap(response => {
        if (response.token) {
          localStorage.setItem('auth_token', response.token);
        }
      })
    );
  }

  logout(): void {
    localStorage.removeItem('auth_token');
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem('auth_token');
  }
}</code></pre>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">POST /api/auth/register</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Register a new user account</p>
                                        <p><strong>Request Body:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}</code></pre>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>register(userData: any): Observable&lt;any&gt; {
  return this.api.post('auth/register', userData);
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Tours Endpoints -->
                            <div class="mb-5">
                                <h4 class="mb-3">Tours Endpoints</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">GET /api/tours</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Get list of all tours with pagination and filters</p>
                                        <p><strong>Query Parameters:</strong></p>
                                        <ul>
                                            <li><code>page</code> - Page number (default: 1)</li>
                                            <li><code>per_page</code> - Items per page (default: 15)</li>
                                            <li><code>category</code> - Filter by category slug</li>
                                            <li><code>destination</code> - Filter by destination slug</li>
                                            <li><code>search</code> - Search term</li>
                                        </ul>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// tour.service.ts
import { Injectable } from '@@angular/core';
import { ApiService } from './api.service';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@@Injectable({
  providedIn: 'root'
})
export class TourService {
  constructor(private api: ApiService) {}

  getTours(params?: any): Observable&lt;any&gt; {
    let endpoint = 'tours';
    if (params) {
      const queryString = new URLSearchParams(params).toString();
      endpoint += `?${queryString}`;
    }
    return this.api.get(endpoint);
  }

  getTour(slug: string): Observable&lt;any&gt; {
    return this.api.get(`tours/${slug}`);
  }

  getTourStats(): Observable&lt;any&gt; {
    return this.api.get('tours/stats');
  }
}

// tour.component.ts
import { Component, OnInit } from '@@angular/core';
import { TourService } from './tour.service';

@@Component({
  selector: 'app-tours',
  templateUrl: './tours.component.html'
})
export class ToursComponent implements OnInit {
  tours: any[] = [];
  loading = false;
  currentPage = 1;

  constructor(private tourService: TourService) {}

  ngOnInit() {
    this.loadTours();
  }

  loadTours() {
    this.loading = true;
    this.tourService.getTours({ page: this.currentPage }).subscribe({
      next: (response) => {
        this.tours = response.data || response;
        this.loading = false;
      },
      error: (error) => {
        console.error('Error loading tours:', error);
        this.loading = false;
      }
    });
  }
}</code></pre>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">GET /api/tours/{slug}</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Get detailed information about a specific tour</p>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// tour-detail.component.ts
import { Component, OnInit } from '@@angular/core';
import { ActivatedRoute } from '@@angular/router';
import { TourService } from './tour.service';

@@Component({
  selector: 'app-tour-detail',
  templateUrl: './tour-detail.component.html'
})
export class TourDetailComponent implements OnInit {
  tour: any = null;
  loading = false;

  constructor(
    private route: ActivatedRoute,
    private tourService: TourService
  ) {}

  ngOnInit() {
    const slug = this.route.snapshot.paramMap.get('slug');
    if (slug) {
      this.loadTour(slug);
    }
  }

  loadTour(slug: string) {
    this.loading = true;
    this.tourService.getTour(slug).subscribe({
      next: (tour) => {
        this.tour = tour;
        this.loading = false;
      },
      error: (error) => {
        console.error('Error loading tour:', error);
        this.loading = false;
      }
    });
  }
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Categories Endpoints -->
                            <div class="mb-5">
                                <h4 class="mb-3">Categories Endpoints</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">GET /api/categories</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Get list of all tour categories</p>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// category.service.ts
@@Injectable({
  providedIn: 'root'
})
export class CategoryService {
  constructor(private api: ApiService) {}

  getCategories(): Observable&lt;any&gt; {
    return this.api.get('categories');
  }

  getCategory(slug: string): Observable&lt;any&gt; {
    return this.api.get(`categories/${slug}`);
  }

  getCategoryTours(slug: string): Observable&lt;any&gt; {
    return this.api.get(`categories/${slug}/tours`);
  }
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Endpoints -->
                            <div class="mb-5">
                                <h4 class="mb-3">Cart Endpoints</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">POST /api/cart/tours/append</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Add a tour to the cart</p>
                                        <p><strong>Request Body:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>{
  "tour_id": 1,
  "adults": 2,
  "children": 1,
  "infants": 0,
  "date": "2024-12-25",
  "options": [1, 2]
}</code></pre>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// cart.service.ts
@@Injectable({
  providedIn: 'root'
})
export class CartService {
  constructor(private api: ApiService) {}

  addTour(tourData: any): Observable&lt;any&gt; {
    return this.api.post('cart/tours/append', tourData);
  }

  getCart(): Observable&lt;any&gt; {
    return this.api.get('cart/list');
  }

  removeItem(itemId: number): Observable&lt;any&gt; {
    return this.api.delete(`cart/remove/${itemId}`);
  }

  clearCart(): Observable&lt;any&gt; {
    return this.api.delete('cart/clear');
  }
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Endpoints -->
                            <div class="mb-5">
                                <h4 class="mb-3">Booking Endpoints</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0">POST /api/bookings</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Create a new booking</p>
                                        <p><strong>Request Body:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "address": "123 Main St",
  "city": "New York",
  "country": "USA",
  "payment_method": "paypal"
}</code></pre>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// booking.service.ts
@@Injectable({
  providedIn: 'root'
})
export class BookingService {
  constructor(private api: ApiService) {}

  createBooking(bookingData: any): Observable&lt;any&gt; {
    return this.api.post('bookings', bookingData);
  }

  getBookings(): Observable&lt;any&gt; {
    return this.api.get('bookings');
  }

  getBooking(id: number): Observable&lt;any&gt; {
    return this.api.get(`bookings/${id}`);
  }
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Wishlist Endpoints (Authenticated) -->
                            <div class="mb-5">
                                <h4 class="mb-3">Wishlist Endpoints (Requires Authentication)</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">GET /api/wishlist</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Get user's wishlist</p>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// wishlist.service.ts
@@Injectable({
  providedIn: 'root'
})
export class WishlistService {
  constructor(private api: ApiService) {}

  getWishlist(): Observable&lt;any&gt; {
    return this.api.get('wishlist');
  }

  toggleWishlist(tourId: number): Observable&lt;any&gt; {
    return this.api.put(`wishlist/${tourId}/toggle`, {});
  }
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Endpoints (Authenticated) -->
                            <div class="mb-5">
                                <h4 class="mb-3">Profile Endpoints (Requires Authentication)</h4>
                                
                                <div class="card mb-3">
                                    <div class="card-header bg-dark text-white">
                                        <h6 class="mb-0">GET /api/profile/me</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Description:</strong> Get current user profile</p>
                                        <p><strong>Angular Example:</strong></p>
                                        <pre class="bg-light p-3 rounded"><code>// profile.service.ts
@@Injectable({
  providedIn: 'root'
})
export class ProfileService {
  constructor(private api: ApiService) {}

  getProfile(): Observable&lt;any&gt; {
    return this.api.get('profile/me');
  }

  updateProfile(profileData: any): Observable&lt;any&gt; {
    return this.api.put('profile', profileData);
  }

  updateProfileImage(image: File): Observable&lt;any&gt; {
    const formData = new FormData();
    formData.append('image', image);
    
    const token = localStorage.getItem('auth_token');
    const headers = new HttpHeaders({
      'Authorization': token ? `Bearer ${token}` : ''
    });
    
    return this.api.http.post(`${this.api.baseUrl}/profile/change/image`, formData, { headers });
  }

  logout(): Observable&lt;any&gt; {
    return this.api.post('profile/logout', {});
  }
}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Handling -->
                            <div class="mb-5">
                                <h4 class="mb-3">Error Handling in Angular</h4>
                                <pre class="bg-light p-3 rounded"><code>// error.interceptor.ts
import { Injectable } from '@@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpErrorResponse } from '@@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Router } from '@@angular/router';

@@Injectable()
export class ErrorInterceptor implements HttpInterceptor {
  constructor(private router: Router) {}

  intercept(req: HttpRequest&lt;any&gt;, next: HttpHandler): Observable&lt;any&gt; {
    return next.handle(req).pipe(
      catchError((error: HttpErrorResponse) => {
        if (error.status === 401) {
          // Unauthorized - redirect to login
          localStorage.removeItem('auth_token');
          this.router.navigate(['/login']);
        } else if (error.status === 403) {
          // Forbidden
          console.error('Access forbidden');
        } else if (error.status === 404) {
          // Not found
          console.error('Resource not found');
        } else if (error.status >= 500) {
          // Server error
          console.error('Server error:', error.message);
        }
        
        return throwError(() => error);
      })
    );
  }
}

// app.module.ts or app.config.ts
import { HTTP_INTERCEPTORS } from '@@angular/common/http';
import { ErrorInterceptor } from './interceptors/error.interceptor';

providers: [
  {
    provide: HTTP_INTERCEPTORS,
    useClass: ErrorInterceptor,
    multi: true
  }
]</code></pre>
                            </div>

                            <!-- Complete Example Component -->
                            <div class="mb-5">
                                <h4 class="mb-3">Complete Example: Tour Listing Component</h4>
                                <pre class="bg-light p-3 rounded"><code>// tours.component.ts
import { Component, OnInit } from '@@angular/core';
import { TourService } from './services/tour.service';
import { CategoryService } from './services/category.service';

@@Component({
  selector: 'app-tours',
  template: `
    &lt;div class="container"&gt;
      &lt;div *ngIf="loading" class="text-center"&gt;
        &lt;p&gt;Loading tours...&lt;/p&gt;
      &lt;/div&gt;
      
      &lt;div *ngIf="!loading && tours.length === 0"&gt;
        &lt;p&gt;No tours found.&lt;/p&gt;
      &lt;/div&gt;
      
      &lt;div class="row"&gt;
        &lt;div class="col-md-4" *ngFor="let tour of tours"&gt;
          &lt;div class="card mb-4"&gt;
            &lt;img [src]="tour.featured_image" class="card-img-top" [alt]="tour.title"&gt;
            &lt;div class="card-body"&gt;
              &lt;h5 class="card-title"&gt;@{{ tour.title }}&lt;/h5&gt;
              &lt;p class="card-text" [innerHTML]="tour.overview"&gt;&lt;/p&gt;
              &lt;p class="text-primary"&gt;Price: $@{{ tour.adult_price }}&lt;/p&gt;
              &lt;a [routerLink]="['/tours', tour.slug]" class="btn btn-primary"&gt;View Details&lt;/a&gt;
            &lt;/div&gt;
          &lt;/div&gt;
        &lt;/div&gt;
      &lt;/div&gt;
      
      &lt;nav *ngIf="totalPages > 1"&gt;
        &lt;ul class="pagination"&gt;
          &lt;li class="page-item" [class.disabled]="currentPage === 1"&gt;
            &lt;a class="page-link" (click)="loadPage(currentPage - 1)"&gt;Previous&lt;/a&gt;
          &lt;/li&gt;
          &lt;li class="page-item" *ngFor="let page of pages" [class.active]="page === currentPage"&gt;
            &lt;a class="page-link" (click)="loadPage(page)"&gt;@{{ page }}&lt;/a&gt;
          &lt;/li&gt;
          &lt;li class="page-item" [class.disabled]="currentPage === totalPages"&gt;
            &lt;a class="page-link" (click)="loadPage(currentPage + 1)"&gt;Next&lt;/a&gt;
          &lt;/li&gt;
        &lt;/ul&gt;
      &lt;/nav&gt;
    &lt;/div&gt;
  `
})
export class ToursComponent implements OnInit {
  tours: any[] = [];
  loading = false;
  currentPage = 1;
  totalPages = 1;
  pages: number[] = [];

  constructor(
    private tourService: TourService,
    private categoryService: CategoryService
  ) {}

  ngOnInit() {
    this.loadTours();
  }

  loadTours() {
    this.loading = true;
    this.tourService.getTours({ page: this.currentPage }).subscribe({
      next: (response) => {
        this.tours = response.data || response;
        this.totalPages = response.last_page || 1;
        this.pages = Array.from({ length: this.totalPages }, (_, i) => i + 1);
        this.loading = false;
      },
      error: (error) => {
        console.error('Error loading tours:', error);
        this.loading = false;
      }
    });
  }

  loadPage(page: number) {
    if (page >= 1 && page <= this.totalPages) {
      this.currentPage = page;
      this.loadTours();
    }
  }
}</code></pre>
                            </div>

                            <!-- All API Endpoints from Swagger -->
                            @if(isset($apiDocs['paths']) && !empty($apiDocs['paths']))
                            <div class="mb-5">
                                <h4 class="mb-3">Complete API Endpoints Reference</h4>
                                @php
                                    $totalEndpoints = 0;
                                    foreach ($apiDocs['paths'] as $path => $methods) {
                                        $totalEndpoints += count($methods);
                                    }
                                @endphp
                                <p class="text-muted">
                                    All available endpoints organized by category. 
                                    <strong>Total: {{ $totalEndpoints }} endpoints</strong> across {{ count($apiDocs['paths'] ?? []) }} paths.
                                </p>
                                
                                @php
                                    // Group endpoints by tags
                                    $endpointsByTag = [];
                                    $untaggedEndpoints = [];
                                    $tags = isset($apiDocs['tags']) ? collect($apiDocs['tags'])->pluck('name')->toArray() : [];
                                    
                                    foreach ($apiDocs['paths'] as $path => $methods) {
                                        foreach ($methods as $method => $details) {
                                            $endpointData = [
                                                'path' => $path,
                                                'method' => strtoupper($method),
                                                'details' => $details
                                            ];
                                            
                                            if (isset($details['tags']) && !empty($details['tags'])) {
                                                $tag = $details['tags'][0];
                                                if (!isset($endpointsByTag[$tag])) {
                                                    $endpointsByTag[$tag] = [];
                                                }
                                                $endpointsByTag[$tag][] = $endpointData;
                                            } else {
                                                // Endpoints without tags go to "Other" category
                                                $untaggedEndpoints[] = $endpointData;
                                            }
                                        }
                                    }
                                    
                                    // Add untagged endpoints to a special category
                                    if (!empty($untaggedEndpoints)) {
                                        $endpointsByTag['Other'] = $untaggedEndpoints;
                                    }
                                    
                                    // Sort tags
                                    ksort($endpointsByTag);
                                @endphp
                                
                                @foreach($endpointsByTag as $tag => $endpoints)
                                <div class="card mb-4">
                                    <div class="card-header bg-secondary text-white">
                                        <h5 class="mb-0">{{ $tag }}</h5>
                                    </div>
                                    <div class="card-body">
                                        @foreach($endpoints as $endpoint)
                                        @php
                                            $method = $endpoint['method'];
                                            $path = $endpoint['path'];
                                            $details = $endpoint['details'];
                                            $summary = $details['summary'] ?? '';
                                            $description = $details['description'] ?? '';
                                            $operationId = $details['operationId'] ?? '';
                                            
                                            // Determine method color
                                            $methodColors = [
                                                'GET' => 'success',
                                                'POST' => 'primary',
                                                'PUT' => 'warning',
                                                'PATCH' => 'info',
                                                'DELETE' => 'danger'
                                            ];
                                            $methodColor = $methodColors[$method] ?? 'secondary';
                                            
                                            // Get parameters
                                            $parameters = $details['parameters'] ?? [];
                                            $requestBody = $details['requestBody'] ?? null;
                                        @endphp
                                        
                                        <div class="mb-4 border-bottom pb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-{{ $methodColor }} me-2">{{ $method }}</span>
                                                <code class="text-dark">{{ $path }}</code>
                                            </div>
                                            
                                            @if($summary)
                                            <h6 class="mt-2 mb-2">{{ $summary }}</h6>
                                            @endif
                                            
                                            @if($description)
                                            <div class="text-muted mb-2">
                                                {!! strip_tags($description, '<pre><code><strong><em><ul><ol><li><p>') !!}
                                            </div>
                                            @endif
                                            
                                            @if(!empty($parameters))
                                            <div class="mb-2">
                                                <strong>Parameters:</strong>
                                                <ul class="mb-0">
                                                    @foreach($parameters as $param)
                                                    <li>
                                                        <code>{{ $param['name'] }}</code>
                                                        @if(isset($param['required']) && $param['required'])
                                                        <span class="badge bg-danger">required</span>
                                                        @endif
                                                        @if(isset($param['description']))
                                                        - {{ $param['description'] }}
                                                        @endif
                                                        @if(isset($param['schema']['type']))
                                                        <span class="text-muted">({{ $param['schema']['type'] }})</span>
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            
                                            @if($requestBody)
                                            <div class="mb-2">
                                                <strong>Request Body:</strong>
                                                @if(isset($requestBody['content']['application/json']['schema']))
                                                @php
                                                    $schema = $requestBody['content']['application/json']['schema'];
                                                    $example = $schema['example'] ?? null;
                                                    $properties = $schema['properties'] ?? [];
                                                @endphp
                                                @if($example)
                                                <pre class="bg-light p-2 rounded mt-2"><code>{{ json_encode($example, JSON_PRETTY_PRINT) }}</code></pre>
                                                @elseif(!empty($properties))
                                                <pre class="bg-light p-2 rounded mt-2"><code>{{ json_encode(collect($properties)->map(function($prop, $key) { return $prop['example'] ?? null; })->filter()->toArray(), JSON_PRETTY_PRINT) }}</code></pre>
                                                @endif
                                                @endif
                                            </div>
                                            @endif
                                            
                                            <!-- Angular Example -->
                                            <div class="mt-3">
                                                <strong>Angular Example:</strong>
                                                <pre class="bg-light p-3 rounded mt-2"><code>// {{ str_replace(['-', '_'], '', $operationId) }}.service.ts
@@Injectable({
  providedIn: 'root'
})
export class {{ ucfirst(str_replace(['-', '_'], '', $tag)) }}Service {
  constructor(private api: ApiService) {}

  {{ lcfirst(str_replace([' ', '-', '_'], '', $summary)) }}(
@if(!empty($parameters))
    @foreach($parameters as $index => $param)
    {{ $param['name'] }}: {{ isset($param['schema']['type']) ? ($param['schema']['type'] === 'number' || $param['schema']['type'] === 'integer' ? 'number' : 'string') : 'any' }}{{ $index < count($parameters) - 1 || $requestBody ? ',' : '' }}
    @endforeach
@endif
@if($requestBody)
    data: any
@endif
  ): Observable&lt;any&gt; {
@if($method === 'GET')
    @if(!empty($parameters))
    let endpoint = '{{ str_replace('/api/', '', $path) }}';
    @if(collect($parameters)->where('in', 'path')->count() > 0)
    @foreach(collect($parameters)->where('in', 'path') as $pathParam)
    endpoint = endpoint.replace('{{ '{' . $pathParam['name'] . '}' }}', {{ $pathParam['name'] }}.toString());
    @endforeach
    @endif
    @if(collect($parameters)->where('in', 'query')->count() > 0)
    const queryParams = new URLSearchParams();
    @foreach(collect($parameters)->where('in', 'query') as $queryParam)
    if ({{ $queryParam['name'] }}) {
      queryParams.append('{{ $queryParam['name'] }}', {{ $queryParam['name'] }}.toString());
    }
    @endforeach
    endpoint += `?${queryParams.toString()}`;
    @endif
    return this.api.get(endpoint);
    @else
    return this.api.get('{{ str_replace('/api/', '', $path) }}');
    @endif
@elseif($method === 'POST')
    @if(collect($parameters)->where('in', 'path')->count() > 0)
    let endpoint = '{{ str_replace('/api/', '', $path) }}';
    @foreach(collect($parameters)->where('in', 'path') as $pathParam)
    endpoint = endpoint.replace('{{ '{' . $pathParam['name'] . '}' }}', {{ $pathParam['name'] }}.toString());
    @endforeach
    return this.api.post(endpoint, data);
    @else
    return this.api.post('{{ str_replace('/api/', '', $path) }}', data);
    @endif
@elseif($method === 'PUT' || $method === 'PATCH')
    @if(collect($parameters)->where('in', 'path')->count() > 0)
    let endpoint = '{{ str_replace('/api/', '', $path) }}';
    @foreach(collect($parameters)->where('in', 'path') as $pathParam)
    endpoint = endpoint.replace('{{ '{' . $pathParam['name'] . '}' }}', {{ $pathParam['name'] }}.toString());
    @endforeach
    return this.api.put(endpoint, data);
    @else
    return this.api.put('{{ str_replace('/api/', '', $path) }}', data);
    @endif
@elseif($method === 'DELETE')
    @if(collect($parameters)->where('in', 'path')->count() > 0)
    let endpoint = '{{ str_replace('/api/', '', $path) }}';
    @foreach(collect($parameters)->where('in', 'path') as $pathParam)
    endpoint = endpoint.replace('{{ '{' . $pathParam['name'] . '}' }}', {{ $pathParam['name'] }}.toString());
    @endforeach
    return this.api.delete(endpoint);
    @else
    return this.api.delete('{{ str_replace('/api/', '', $path) }}');
    @endif
@endif
  }
}</code></pre>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                                
                                @if(empty($endpointsByTag))
                                <div class="alert alert-info">
                                    <p class="mb-0">No endpoints found in the Swagger documentation. Please run <code>php artisan l5-swagger:generate</code> to generate the API documentation.</p>
                                </div>
                                @else
                                <!-- Endpoint Summary -->
                                <div class="card mt-4">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Endpoint Summary by Category</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($endpointsByTag as $tag => $endpoints)
                                            <div class="col-md-4 mb-2">
                                                <strong>{{ $tag }}:</strong> {{ count($endpoints) }} endpoint(s)
                                            </div>
                                            @endforeach
                                        </div>
                                        <hr>
                                        <p class="mb-0"><strong>Total Endpoints:</strong> {{ $totalEndpoints }} | <strong>Total Categories:</strong> {{ count($endpointsByTag) }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <h5>Swagger Documentation Not Found</h5>
                                <p class="mb-0">The API documentation file was not found. Please run <code>php artisan l5-swagger:generate</code> to generate the Swagger documentation JSON file.</p>
                            </div>
                            @endif

                            <!-- Notes -->
                            <div class="alert alert-warning">
                                <h5>Important Notes:</h5>
                                <ul class="mb-0">
                                    <li>Always include the <code>Accept-Language</code> header for localized content</li>
                                    <li>Protected endpoints require authentication via Bearer token</li>
                                    <li>All dates should be in ISO 8601 format (YYYY-MM-DD)</li>
                                    <li>File uploads should use <code>multipart/form-data</code></li>
                                    <li>API responses follow RESTful conventions</li>
                                    <li>Error responses include status codes and error messages</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection

@push('css')
<style>
    pre {
        max-height: 500px;
        overflow-y: auto;
    }
    code {
        font-size: 0.9em;
    }
    .card-header h6 {
        margin: 0;
    }
</style>
@endpush
