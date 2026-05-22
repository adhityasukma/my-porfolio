/**
 * Portfolio Repeater Control JavaScript
 * Handles add/delete/pagination functionality for the custom repeater control
 */

(function($) {
    'use strict';

    // Initialize when customizer is ready
    wp.customize.bind('ready', function() {
        initPortfolioRepeater();
    });

    function initPortfolioRepeater() {
        var $containers = $('.portfolio-repeater-control');
        
        $containers.each(function() {
            var $container = $(this);
            var $items = $container.find('.portfolio-repeater-items');
            var $value = $container.find('.portfolio-repeater-value');
            var itemsPerPage = parseInt($items.data('items-per-page')) || 9;
            var currentPage = 1;

            // Initialize pagination
            updatePagination();

            // Add new project
            $container.on('click', '.portfolio-repeater-add', function(e) {
                e.preventDefault();
                addNewItem();
            });

            // Delete project
            $container.on('click', '.repeater-delete', function(e) {
                e.preventDefault();
                if (confirm(portfolioRepeater.confirmDelete)) {
                    var $item = $(this).closest('.portfolio-repeater-item');
                    $item.slideUp(300, function() {
                        $(this).remove();
                        reindexItems();
                        updateValue();
                        updatePagination();
                    });
                }
            });

            // Toggle project content
            $container.on('click', '.repeater-toggle', function(e) {
                e.preventDefault();
                var $item = $(this).closest('.portfolio-repeater-item');
                var $content = $item.find('.repeater-item-content');
                var $icon = $(this).find('.dashicons');
                
                $content.slideToggle(200);
                $icon.toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');
            });

            // Update value on input change
            $container.on('input change', '.repeater-input', function() {
                var $item = $(this).closest('.portfolio-repeater-item');
                var field = $(this).data('field');
                
                // Update title preview if title field changed
                if (field === 'title') {
                    var titleText = $(this).val() || 'Project ' + (parseInt($item.data('index')) + 1);
                    $item.find('.title-text').text(titleText);
                }
                
                // Update image preview
                if (field === 'image') {
                    var imageUrl = $(this).val();
                    var $preview = $item.find('.repeater-image-preview');
                    if (imageUrl) {
                        $preview.find('img').attr('src', imageUrl);
                        $preview.show();
                    } else {
                        $preview.hide();
                    }
                }
                
                updateValue();
            });

            // Image upload button
            $container.on('click', '.repeater-upload-image', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $input = $button.siblings('.repeater-image-url');
                var $preview = $button.closest('.repeater-field').find('.repeater-image-preview');

                var mediaUploader = wp.media({
                    title: 'Select Project Image',
                    button: { text: 'Use This Image' },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $input.val(attachment.url).trigger('change');
                    $preview.find('img').attr('src', attachment.url);
                    $preview.show();
                });

                mediaUploader.open();
            });

            // Pagination: Previous
            $container.on('click', '.pagination-prev', function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            // Pagination: Next
            $container.on('click', '.pagination-next', function(e) {
                e.preventDefault();
                var totalPages = getTotalPages();
                if (currentPage < totalPages) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            function addNewItem() {
                var template = $('#tmpl-portfolio-repeater-item').html();
                var newIndex = $items.find('.portfolio-repeater-item').length;
                
                var newItem = template
                    .replace(/\{\{index\}\}/g, newIndex)
                    .replace(/\{\{num\}\}/g, newIndex + 1);
                
                var $newItem = $(newItem);
                $newItem.find('.title-text').text('Project ' + (newIndex + 1));
                $newItem.hide();
                
                $items.append($newItem);
                $newItem.slideDown(300);
                
                // Open the new item
                $newItem.find('.repeater-item-content').slideDown(200);
                $newItem.find('.repeater-toggle .dashicons')
                    .removeClass('dashicons-arrow-down-alt2')
                    .addClass('dashicons-arrow-up-alt2');
                
                updateValue();
                updatePagination();
                
                // Navigate to the last page where new item is added
                var totalPages = getTotalPages();
                if (currentPage !== totalPages) {
                    currentPage = totalPages;
                    showPage(currentPage);
                }
            }

            function reindexItems() {
                $items.find('.portfolio-repeater-item').each(function(index) {
                    $(this).attr('data-index', index);
                    var $titleText = $(this).find('.title-text');
                    var titleInput = $(this).find('.repeater-input[data-field="title"]').val();
                    if (!titleInput) {
                        $titleText.text('Project ' + (index + 1));
                    }
                });
            }

            function updateValue() {
                var projects = [];
                
                $items.find('.portfolio-repeater-item').each(function() {
                    var project = {};
                    $(this).find('.repeater-input').each(function() {
                        var field = $(this).data('field');
                        project[field] = $(this).val();
                    });
                    projects.push(project);
                });
                
                $value.val(JSON.stringify(projects)).trigger('change');
            }

            function getTotalPages() {
                var totalItems = $items.find('.portfolio-repeater-item').length;
                return Math.max(1, Math.ceil(totalItems / itemsPerPage));
            }

            function showPage(page) {
                var $allItems = $items.find('.portfolio-repeater-item');
                var start = (page - 1) * itemsPerPage;
                var end = start + itemsPerPage;
                
                $allItems.hide();
                $allItems.slice(start, end).show();
                
                updatePaginationUI();
            }

            function updatePagination() {
                var totalPages = getTotalPages();
                
                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
                
                showPage(currentPage);
            }

            function updatePaginationUI() {
                var totalPages = getTotalPages();
                var $pagination = $container.find('.portfolio-repeater-pagination');
                
                $pagination.find('.current-page').text(currentPage);
                $pagination.find('.total-pages').text(totalPages);
                
                $pagination.find('.pagination-prev').prop('disabled', currentPage <= 1);
                $pagination.find('.pagination-next').prop('disabled', currentPage >= totalPages);
                
                // Hide pagination if only one page
                if (totalPages <= 1) {
                    $pagination.hide();
                } else {
                    $pagination.show();
                }
            }
        });
    }

})(jQuery);
