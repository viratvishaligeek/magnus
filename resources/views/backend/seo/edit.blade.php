@extends('backend.include.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 font-weight-bold text-dark mb-0">{{ $pageName }}</h2>
            <a href="{{ route('admin.seo-plugin.index', $type) }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.seo-plugin.update', ['type' => $type, 'id' => $data->id]) }}">
            @csrf
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <!-- Basic Meta Block (5 Fields Configured Here) -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white font-weight-bold py-3 text-primary">Snippet Optimization</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label font-weight-bold">Meta Title</label>
                                    <span id="titleCount" class="badge bg-light text-secondary">0 / 60</span>
                                </div>
                                <input type="text" name="meta_title" class="form-control border-gray"
                                    value="{{ old('meta_title') ?? ($data->seo->meta_title ?? '') }}" maxlength="100">
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label font-weight-bold">Meta Description</label>
                                    <span id="descCount" class="badge bg-light text-secondary">0 / 160</span>
                                </div>
                                <textarea name="meta_description" class="form-control" rows="3" maxlength="255">{{ old('meta_description') ?? ($data->seo->meta_description ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Focus Keywords (Comma Separated)</label>
                                <input type="text" name="meta_keywords" class="form-control"
                                    value="{{ old('meta_keywords') ?? ($data->seo->meta_keywords ?? '') }}"
                                    placeholder="e.g., laravel, headless nextjs, webapp">
                            </div>

                            <div class="mb-0">
                                <label class="form-label font-weight-bold">Meta Author</label>
                                <input type="text" name="meta_author" class="form-control"
                                    value="{{ old('meta_author') ?? ($data->seo->meta_author ?? '') }}"
                                    placeholder="e.g., Brand Editorial Team">
                            </div>
                        </div>
                    </div>

                    <!-- Crawler Configuration Module -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white font-weight-bold py-3">Robots Engine Instructions</div>
                        <div class="card-body">
                            <div class="mb-0">
                                <label class="form-label font-weight-bold">Robots Meta Tag Configuration</label>
                                <select name="meta_robots" class="form-select form-control">
                                    <option value="index,follow"
                                        {{ ($data->seo->meta_robots ?? '') == 'index,follow' ? 'selected' : '' }}>Index,
                                        Follow (Recommended)</option>
                                    <option value="noindex,nofollow"
                                        {{ ($data->seo->meta_robots ?? '') == 'noindex,nofollow' ? 'selected' : '' }}>
                                        No-Index, No-Follow</option>
                                    <option value="noindex,follow"
                                        {{ ($data->seo->meta_robots ?? '') == 'noindex,follow' ? 'selected' : '' }}>
                                        No-Index, Follow</option>
                                    <option value="index,nofollow"
                                        {{ ($data->seo->meta_robots ?? '') == 'index,nofollow' ? 'selected' : '' }}>Index,
                                        No-Follow</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Social Open Graph Structure (8 Fields Inside Tabs) -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white font-weight-bold py-3 text-info">Social Graph Mapping (Open Graph &
                            Twitter Share)</div>
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" id="socialTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="facebook-tab" data-bs-toggle="tab"
                                        data-bs-target="#facebook" type="button" role="tab">Facebook (OG)</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="twitter-tab" data-bs-toggle="tab" data-bs-target="#twitter"
                                        type="button" role="tab">Twitter Meta</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="socialTabContent">
                                <div class="tab-pane fade show active" id="facebook" role="tabpanel">
                                    <div class="mb-2">
                                        <label class="small text-muted font-weight-bold">OG Title</label>
                                        <input type="text" name="og_title" placeholder="OG Custom Title"
                                            class="form-control"
                                            value="{{ old('og_title') ?? ($data->seo->og_title ?? '') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted font-weight-bold">OG Description</label>
                                        <textarea name="og_description" class="form-control" placeholder="OG Custom Description" rows="2">{{ old('og_description') ?? ($data->seo->og_description ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted font-weight-bold">OG Target URL</label>
                                        <input type="url" name="og_url" class="form-control"
                                            placeholder="https://site.com/target-path"
                                            value="{{ old('og_url') ?? ($data->seo->og_url ?? '') }}">
                                    </div>
                                    <div>
                                        <label class="small text-muted font-weight-bold">OG Image URL</label>
                                        <input type="text" name="og_image" class="form-control"
                                            placeholder="OG Storage Asset Link"
                                            value="{{ old('og_image') ?? ($data->seo->og_image ?? '') }}">
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="twitter" role="tabpanel">
                                    <div class="mb-2">
                                        <label class="small text-muted font-weight-bold">Twitter Title</label>
                                        <input type="text" name="twitter_title"
                                            placeholder="Twitter Display Card Title" class="form-control"
                                            value="{{ old('twitter_title') ?? ($data->seo->twitter_title ?? '') }}">
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted font-weight-bold">Twitter Description</label>
                                        <textarea name="twitter_description" class="form-control" placeholder="Twitter Description Breakdown"
                                            rows="2">{{ old('twitter_description') ?? ($data->seo->twitter_description ?? '') }}</textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small text-muted font-weight-bold">Twitter Card Redirect URL</label>
                                        <input type="url" name="twitter_url" class="form-control"
                                            placeholder="https://site.com/target-path"
                                            value="{{ old('twitter_url') ?? ($data->seo->twitter_url ?? '') }}">
                                    </div>
                                    <div>
                                        <label class="small text-muted font-weight-bold">Twitter Image Path</label>
                                        <input type="text" name="twitter_image" class="form-control"
                                            placeholder="Twitter Asset Share Image Link"
                                            value="{{ old('twitter_image') ?? ($data->seo->twitter_image ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schema Block (1 Field) -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white font-weight-bold py-3 text-dark">JSON-LD Structural Engine Schema
                        </div>
                        <div class="card-body">
                            <textarea name="schema" class="form-control text-monospace bg-light" rows="6" style="font-family: monospace;"
                                placeholder=''>{{ old('schema') ?? (is_array($data->seo->schema ?? null) ? json_encode($data->seo->schema, JSON_PRETTY_PRINT) : $data->seo->schema ?? '') }}</textarea> <small class="text-danger d-none" id="schemaError">Malformed JSON
                                structure identified. Please
                                check syntax.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-5">
                        <button class="btn btn-primary px-5 shadow-sm" type="submit">Publish SEO Matrix</button>
                    </div>
                </div>

                <!-- Analysis Sidebar Component -->
                <div class="col-xl-4 col-lg-5">
                    <div class="sticky-top" style="top: 24px; z-index: 1020;">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white font-weight-bold py-3 text-success">Google SERP Simulator
                            </div>
                            <div class="card-body bg-white">
                                <div class="mb-1 text-truncate text-muted small" id="preview-url" style="color:#202124;">
                                    {{ url('/') }}/{{ $data->slug ?? Str::slug($data->name ?? '') }}
                                </div>
                                <h5 class="h5 mb-1 text-truncate" id="preview-title"
                                    style="color:#1a0dab; font-family: Roboto, Arial, sans-serif;">
                                    {{ $data->seo->meta_title ?? 'Meta Title Placeholder' }}
                                </h5>
                                <p class="small text-secondary m-0 text-wrap" id="preview-desc"
                                    style="color:#4d5156; font-size: 14px; line-height: 1.58;">
                                    {{ $data->seo->meta_description ?? 'Provide a descriptive meta payload here to track snippet length and readability...' }}
                                </p>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div
                                class="card-header bg-white font-weight-bold py-3 d-flex justify-content-between align-items-center">
                                <span>Real-time Health Engine</span>
                                <span id="seoScore" class="badge bg-danger fs-6 px-3 py-2">0%</span>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush small" id="checklist"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const titleInput = document.querySelector('[name="meta_title"]');
            const descInput = document.querySelector('[name="meta_description"]');
            const keywordsInput = document.querySelector('[name="meta_keywords"]');
            const schemaInput = document.querySelector('[name="schema"]');

            function updateLivePrevisualizations() {
                // Live Text Syncing for Google Preview
                document.getElementById('preview-title').innerText = titleInput.value.trim() ||
                    "Meta Title Placeholder";
                document.getElementById('preview-desc').innerText = descInput.value.trim() ||
                    "Provide a descriptive meta payload here to track snippet length...";

                // Length Counters updating UI elements
                document.getElementById('titleCount').innerText = `${titleInput.value.length} / 60`;
                document.getElementById('descCount').innerText = `${descInput.value.length} / 160`;

                // Dynamic color tracking thresholds
                manageCountersColor(titleInput.value.length, document.getElementById('titleCount'), 50, 60);
                manageCountersColor(descInput.value.length, document.getElementById('descCount'), 120, 160);

                evalSeoEngineHealth();
            }

            function manageCountersColor(length, element, min, max) {
                if (length >= min && length <= max) {
                    element.className = "badge bg-success text-white";
                } else if (length > max || (length > 0 && length < min)) {
                    element.className = "badge bg-warning text-dark";
                } else {
                    element.className = "badge bg-light text-secondary";
                }
            }

            function evalSeoEngineHealth() {
                let matches = 0;
                let targetPoints = 0;
                let checklistHTML = '';

                const appendMetric = (passed, message) => {
                    targetPoints++;
                    if (passed) matches++;
                    checklistHTML += `
                    <li class="list-group-item d-flex align-items-center bg-transparent border-0 px-0 py-1">
                        <span class="me-2">${passed ? '✅' : '❌'}</span>
                        <span class="${passed ? 'text-success' : 'text-muted'}">${message}</span>
                    </li>`;
                };

                // Metric Calculations
                appendMetric((titleInput.value.length >= 50 && titleInput.value.length <= 60),
                    "Title length matches 50-60 character standard.");
                appendMetric((descInput.value.length >= 120 && descInput.value.length <= 160),
                    "Description checks out between 120-160 boundary limits.");

                let parsedKeywords = keywordsInput.value.split(',');
                let keywordConfigured = parsedKeywords.length > 0 && parsedKeywords[0].trim() !== "";
                appendMetric(keywordConfigured, "At least one focus meta keyword is deployed.");

                // Check for dynamic social values configuration
                const ogTitle = document.querySelector('[name="og_title"]').value.trim();
                appendMetric((ogTitle.length > 0), "Social graph open graph title metadata populated.");

                // Verification Score rendering
                let calculated = Math.round((matches / targetPoints) * 100) || 0;
                const scoreBadge = document.getElementById('seoScore');
                scoreBadge.innerText = `${calculated}%`;

                if (calculated < 40) scoreBadge.className = "badge bg-danger";
                else if (calculated < 80) scoreBadge.className = "badge bg-warning text-dark";
                else scoreBadge.className = "badge bg-success";

                document.getElementById('checklist').innerHTML = checklistHTML;
            }

            // Schema JSON syntax checker
            schemaInput.addEventListener('input', function() {
                const errElement = document.getElementById('schemaError');
                if (!this.value.trim()) {
                    errElement.classList.add('d-none');
                    return;
                }
                try {
                    JSON.parse(this.value);
                    errElement.classList.add('d-none');
                } catch (e) {
                    errElement.classList.remove('d-none');
                }
            });

            // Tracking Event Attachments
            titleInput.addEventListener('input', updateLivePrevisualizations);
            descInput.addEventListener('input', updateLivePrevisualizations);
            keywordsInput.addEventListener('input', updateLivePrevisualizations);
            document.querySelector('[name="og_title"]').addEventListener('input', evalSeoEngineHealth);

            // Startup execution call
            updateLivePrevisualizations();
        });
    </script>
@endsection
