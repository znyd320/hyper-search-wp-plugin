(function($) {
    'use strict';

    class HyperSearch {
        constructor() {
            this.init();
            this.bindEvents();
            console.log("HyperSearch initialized");
        }

        init() {
            this.searchForms = $('.hyper-search-form');
            this.minChars = 3;
            this.typingTimer = null;
            this.searchDelay = 500;
        }

        bindEvents() {
            this.searchForms.each((index, form) => {
                const $form = $(form);
                const $input = $form.find('.hyper-search-input');
                const $results = $form.find('.hyper-search-results');

                $input.on('input', this.debounce(() => {
                    this.performSearch($input, $results);
                }, this.searchDelay));

                $(document).on('click', (e) => {
                    if (!$form.is(e.target) && $form.has(e.target).length === 0) {
                        $results.removeClass('active');
                    }
                });
            });
        }

        performSearch($input, $results) {
            const query = $input.val();
            const formId = $input.closest('.hyper-search-form').data('form-id');

            if (query.length < this.minChars) {
                $results.removeClass('active').empty();
                return;
            }

            $.ajax({
                url: hyperSearchData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'hyper_search_query',
                    nonce: hyperSearchData.nonce,
                    query: query,
                    form_id: formId
                },
                beforeSend: () => {
                    $input.addClass('searching');
                },
                success: (response) => {
                    if (response.success) {
                        this.renderResults(response.data, $results);
                    }
                },
                complete: () => {
                    $input.removeClass('searching');
                }
            });
        }

        renderResults(results, $results) {
            let html = '';

            if (results.length) {
                results.forEach(item => {
                    html += `
                        <div class="hyper-search-item">
                            <a href="${item.url}">
                                <h4>${item.title}</h4>
                                <p>${item.excerpt.length > 120 ? item.excerpt.substring(0, 120) + '...' : item.excerpt}</p>
                            </a>
                        </div>
                    `;
                });
            } else {
                html = `
                    <div class="hyper-search-item">
                        <p>${hyperSearchData.noResults || 'No results found'}</p>
                    </div>
                `;
            }

            $results.html(html).addClass('active');
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    $(document).ready(() => {
        new HyperSearch();
    });

})(jQuery);
