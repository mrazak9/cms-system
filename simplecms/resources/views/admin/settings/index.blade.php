@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Site Settings')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Settings</div>
@endsection

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Site Settings</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills" id="settingTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active"
                                   id="general-tab"
                                   data-toggle="pill"
                                   href="#general"
                                   role="tab"
                                   aria-controls="general"
                                   aria-selected="true">
                                    <i class="fas fa-cog"></i> General
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="seo-tab"
                                   data-toggle="pill"
                                   href="#seo"
                                   role="tab"
                                   aria-controls="seo"
                                   aria-selected="false">
                                    <i class="fas fa-search"></i> SEO
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="social-tab"
                                   data-toggle="pill"
                                   href="#social"
                                   role="tab"
                                   aria-controls="social"
                                   aria-selected="false">
                                    <i class="fas fa-share-alt"></i> Social Media
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="contact-tab"
                                   data-toggle="pill"
                                   href="#contact"
                                   role="tab"
                                   aria-controls="contact"
                                   aria-selected="false">
                                    <i class="fas fa-envelope"></i> Contact
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   id="analytics-tab"
                                   data-toggle="pill"
                                   href="#analytics"
                                   role="tab"
                                   aria-controls="analytics"
                                   aria-selected="false">
                                    <i class="fas fa-chart-line"></i> Analytics
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="settingTabContent">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="site_name">Site Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control @error('site_name') is-invalid @enderror"
                                                   id="site_name"
                                                   name="site_name"
                                                   value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                                                   required>
                                            @error('site_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="site_tagline">Site Tagline</label>
                                            <input type="text"
                                                   class="form-control @error('site_tagline') is-invalid @enderror"
                                                   id="site_tagline"
                                                   name="site_tagline"
                                                   value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                                                   placeholder="Just another SimpleCMS site">
                                            @error('site_tagline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="site_description">Site Description</label>
                                            <textarea class="form-control @error('site_description') is-invalid @enderror"
                                                      id="site_description"
                                                      name="site_description"
                                                      rows="4">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                                            @error('site_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="mail_from_address">Admin Email <span class="text-danger">*</span></label>
                                            <input type="email"
                                                   class="form-control @error('mail_from_address') is-invalid @enderror"
                                                   id="mail_from_address"
                                                   name="mail_from_address"
                                                   value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                                                   required>
                                            @error('mail_from_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="timezone">Timezone</label>
                                            <select class="form-control @error('timezone') is-invalid @enderror"
                                                    id="timezone"
                                                    name="timezone">
                                                <option value="UTC" {{ old('timezone', $settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                <option value="Asia/Jakarta" {{ old('timezone', $settings['timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                                <option value="America/New_York" {{ old('timezone', $settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                                <option value="Europe/London" {{ old('timezone', $settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                            </select>
                                            @error('timezone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="items_per_page">Items Per Page</label>
                                            <input type="number"
                                                   class="form-control @error('items_per_page') is-invalid @enderror"
                                                   id="items_per_page"
                                                   name="items_per_page"
                                                   value="{{ old('items_per_page', $settings['items_per_page'] ?? 10) }}"
                                                   min="1"
                                                   max="100">
                                            @error('items_per_page')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Site Logo</label>
                                            @if(!empty($settings['site_logo']))
                                                <div class="mb-2">
                                                    @php
                                                        $logoUrl = str_starts_with($settings['site_logo'], '/')
                                                            ? asset($settings['site_logo'])
                                                            : asset('storage/' . $settings['site_logo']);
                                                    @endphp
                                                    <img src="{{ $logoUrl }}"
                                                         alt="Site Logo"
                                                         class="img-thumbnail"
                                                         style="max-width: 100%;">
                                                </div>
                                            @endif
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('site_logo') is-invalid @enderror"
                                                       id="site_logo"
                                                       name="site_logo"
                                                       accept="image/*">
                                                <label class="custom-file-label" for="site_logo">Choose file</label>
                                                @error('site_logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Site Favicon</label>
                                            @if(!empty($settings['site_favicon']))
                                                <div class="mb-2">
                                                    @php
                                                        $faviconUrl = str_starts_with($settings['site_favicon'], '/')
                                                            ? asset($settings['site_favicon'])
                                                            : asset('storage/' . $settings['site_favicon']);
                                                    @endphp
                                                    <img src="{{ $faviconUrl }}"
                                                         alt="Site Favicon"
                                                         class="img-thumbnail"
                                                         style="max-width: 64px;">
                                                </div>
                                            @endif
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input @error('site_favicon') is-invalid @enderror"
                                                       id="site_favicon"
                                                       name="site_favicon"
                                                       accept="image/x-icon,image/png">
                                                <label class="custom-file-label" for="site_favicon">Choose file</label>
                                                @error('site_favicon')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">Recommended: 32x32px or 64x64px</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Settings -->
                            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text"
                                                   class="form-control @error('meta_title') is-invalid @enderror"
                                                   id="meta_title"
                                                   name="meta_title"
                                                   value="{{ old('meta_title', $settings['meta_title'] ?? '') }}">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">If empty, site name will be used</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                                      id="meta_description"
                                                      name="meta_description"
                                                      rows="3">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <span id="meta-desc-count">0</span> characters (recommended 150-160)
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text"
                                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                                   id="meta_keywords"
                                                   name="meta_keywords"
                                                   value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}"
                                                   placeholder="keyword1, keyword2, keyword3">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="google_site_verification">Google Site Verification</label>
                                            <input type="text"
                                                   class="form-control @error('google_site_verification') is-invalid @enderror"
                                                   id="google_site_verification"
                                                   name="google_site_verification"
                                                   value="{{ old('google_site_verification', $settings['google_site_verification'] ?? '') }}"
                                                   placeholder="google-site-verification code">
                                            @error('google_site_verification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="robots_txt">Robots.txt Content</label>
                                            <textarea class="form-control @error('robots_txt') is-invalid @enderror"
                                                      id="robots_txt"
                                                      name="robots_txt"
                                                      rows="5"
                                                      style="font-family: monospace;">{{ old('robots_txt', $settings['robots_txt'] ?? "User-agent: *\nDisallow:") }}</textarea>
                                            @error('robots_txt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media Settings -->
                            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="facebook">
                                                <i class="fab fa-facebook"></i> Facebook URL
                                            </label>
                                            <input type="url"
                                                   class="form-control @error('facebook') is-invalid @enderror"
                                                   id="facebook"
                                                   name="facebook"
                                                   value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                                                   placeholder="https://facebook.com/yourpage">
                                            @error('facebook')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="twitter">
                                                <i class="fab fa-twitter"></i> Twitter URL
                                            </label>
                                            <input type="url"
                                                   class="form-control @error('twitter') is-invalid @enderror"
                                                   id="twitter"
                                                   name="twitter"
                                                   value="{{ old('twitter', $settings['twitter'] ?? '') }}"
                                                   placeholder="https://twitter.com/youraccount">
                                            @error('twitter')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="instagram">
                                                <i class="fab fa-instagram"></i> Instagram URL
                                            </label>
                                            <input type="url"
                                                   class="form-control @error('instagram') is-invalid @enderror"
                                                   id="instagram"
                                                   name="instagram"
                                                   value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                                                   placeholder="https://instagram.com/youraccount">
                                            @error('instagram')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="linkedin">
                                                <i class="fab fa-linkedin"></i> LinkedIn URL
                                            </label>
                                            <input type="url"
                                                   class="form-control @error('linkedin') is-invalid @enderror"
                                                   id="linkedin"
                                                   name="linkedin"
                                                   value="{{ old('linkedin', $settings['linkedin'] ?? '') }}"
                                                   placeholder="https://linkedin.com/company/yourcompany">
                                            @error('linkedin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="youtube">
                                                <i class="fab fa-youtube"></i> YouTube URL
                                            </label>
                                            <input type="url"
                                                   class="form-control @error('youtube') is-invalid @enderror"
                                                   id="youtube"
                                                   name="youtube"
                                                   value="{{ old('youtube', $settings['youtube'] ?? '') }}"
                                                   placeholder="https://youtube.com/c/yourchannel">
                                            @error('youtube')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="whatsapp">
                                                <i class="fab fa-whatsapp"></i> WhatsApp Number
                                            </label>
                                            <input type="text"
                                                   class="form-control @error('whatsapp') is-invalid @enderror"
                                                   id="whatsapp"
                                                   name="whatsapp"
                                                   value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                                                   placeholder="+6281234567890">
                                            @error('whatsapp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Settings -->
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="contact_email">Contact Email</label>
                                            <input type="email"
                                                   class="form-control @error('contact_email') is-invalid @enderror"
                                                   id="contact_email"
                                                   name="contact_email"
                                                   value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                                                   placeholder="contact@example.com">
                                            @error('contact_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="contact_phone">Contact Phone</label>
                                            <input type="text"
                                                   class="form-control @error('contact_phone') is-invalid @enderror"
                                                   id="contact_phone"
                                                   name="contact_phone"
                                                   value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                                                   placeholder="+1 234 567 8900">
                                            @error('contact_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="contact_address">Contact Address</label>
                                            <textarea class="form-control @error('contact_address') is-invalid @enderror"
                                                      id="contact_address"
                                                      name="contact_address"
                                                      rows="3">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                                            @error('contact_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Analytics Settings -->
                            <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="google_analytics">Google Analytics Tracking ID</label>
                                            <input type="text"
                                                   class="form-control @error('google_analytics') is-invalid @enderror"
                                                   id="google_analytics"
                                                   name="google_analytics"
                                                   value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}"
                                                   placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX-X">
                                            @error('google_analytics')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="facebook_pixel_id">Facebook Pixel ID</label>
                                            <input type="text"
                                                   class="form-control @error('facebook_pixel_id') is-invalid @enderror"
                                                   id="facebook_pixel_id"
                                                   name="facebook_pixel_id"
                                                   value="{{ old('facebook_pixel_id', $settings['facebook_pixel_id'] ?? '') }}"
                                                   placeholder="123456789012345">
                                            @error('facebook_pixel_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="custom_head_code">Custom Head Code</label>
                                            <textarea class="form-control @error('custom_head_code') is-invalid @enderror"
                                                      id="custom_head_code"
                                                      name="custom_head_code"
                                                      rows="5"
                                                      style="font-family: monospace;">{{ old('custom_head_code', $settings['custom_head_code'] ?? '') }}</textarea>
                                            @error('custom_head_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Code will be inserted in the &lt;head&gt; section</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="custom_footer_code">Custom Footer Code</label>
                                            <textarea class="form-control @error('custom_footer_code') is-invalid @enderror"
                                                      id="custom_footer_code"
                                                      name="custom_footer_code"
                                                      rows="5"
                                                      style="font-family: monospace;">{{ old('custom_footer_code', $settings['custom_footer_code'] ?? '') }}</textarea>
                                            @error('custom_footer_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Code will be inserted before &lt;/body&gt; tag</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    // Update file input label with filename
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Count meta description characters
    function updateMetaDescCount() {
        let count = $('#meta_description').val().length;
        $('#meta-desc-count').text(count);

        if (count > 160) {
            $('#meta-desc-count').addClass('text-danger');
        } else if (count > 150) {
            $('#meta-desc-count').addClass('text-warning').removeClass('text-danger');
        } else {
            $('#meta-desc-count').removeClass('text-warning text-danger');
        }
    }

    $('#meta_description').on('input', updateMetaDescCount);

    // Trigger count on page load
    $(document).ready(function() {
        updateMetaDescCount();
    });
</script>
@endpush
