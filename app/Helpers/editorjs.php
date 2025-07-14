<?php

/**
 * Render Editor.js JSON data to HTML
 * 
 * @param string $jsonData JSON string from Editor.js
 * @return string HTML output
 */
function render_editorjs($jsonData)
{
    if (empty($jsonData)) {
        return '';
    }

    try {
        $data = json_decode($jsonData, true);

        if (!$data || !isset($data['blocks'])) {
            return '';
        }

        $html = '';

        foreach ($data['blocks'] as $index => $block) {
            $html .= render_editorjs_block($block, $index);
        }

        return $html;
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Render a single Editor.js block
 * 
 * @param array $block Block data from Editor.js
 * @return string HTML output for the block
 */
function render_editorjs_block($block, $index)
{
    $type = $block['type'] ?? '';
    $data = $block['data'] ?? [];

    switch ($type) {
        case 'paragraph':
            return render_paragraph_block($data);

        case 'header':
            return render_header_block($data);

        case 'image':
            return render_image_block($data, $index);

        case 'quote':
            return render_quote_block($data);

        case 'delimiter':
            return render_delimiter_block($data);

        case 'embed':
            return render_embed_block($data);

        case 'columns':
            return render_columns_block($data);

        case 'list':
            return render_list_block($data);

        case 'checklist':
            return render_checklist_block($data);

        case 'code':
            return render_code_block($data);

        case 'table':
            return render_table_block($data);

        default:
            return '';
    }
}

/**
 * Render paragraph block
 */
function render_paragraph_block($data)
{
    $text = $data['text'] ?? '';
    $alignment = $data['alignment'] ?? 'left';

    return '<p style="text-align: ' . htmlspecialchars($alignment) . '">' . $text . '</p>';
}

/**
 * Render header block
 */
function render_header_block($data)
{
    $text = $data['text'] ?? '';
    $level = $data['level'] ?? 2;
    $alignment = $data['alignment'] ?? 'left';

    return '<h' . $level . ' style="text-align: ' . htmlspecialchars($alignment) . '">' . $text . '</h' . $level . '>';
}

/**
 * Render image block
 */
function render_image_block($data, $index)
{
    $url = $data['file']['url'] ?? '';
    $caption = $data['caption'] ?? '';

    // Chuyển đổi thành boolean chính xác
    $stretched = filter_var($data['stretched'] ?? false, FILTER_VALIDATE_BOOLEAN);

    if (empty($url)) {
        return '';
    }

    $classes = ['editor-image'];
    if ($stretched) $classes[] = 'stretched';
    if ($index == 0) $classes[] = 'first';

    $html = '<figure class="' . implode(' ', $classes) . '">';
    $html .= '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($caption) . '">';

    if (!empty($caption)) {
        $html .= '<figcaption>' . htmlspecialchars($caption) . '</figcaption>';
    }

    $html .= '</figure>';

    return $html;
}

/**
 * Render quote block
 */
function render_quote_block($data)
{
    $text = $data['text'] ?? '';
    $caption = $data['caption'] ?? '';
    $alignment = $data['alignment'] ?? 'left';

    $html = '<blockquote style="text-align: ' . htmlspecialchars($alignment) . '">';
    $html .= '<p>' . $text . '</p>';

    if (!empty($caption)) {
        $html .= '<cite>' . htmlspecialchars($caption) . '</cite>';
    }

    $html .= '</blockquote>';

    return $html;
}

/**
 * Render delimiter block
 */
function render_delimiter_block($data)
{
    $style = $data['style'] ?? 'line';
    $lineWidth = $data['lineWidth'] ?? 25;
    $lineThickness = $data['lineThickness'] ?? 2;

    switch ($style) {
        case 'star':
            return render_star_delimiter();
        case 'dash':
            return render_dash_delimiter();
        case 'line':
            return render_line_delimiter($lineWidth, $lineThickness);
        default:
            return render_line_delimiter($lineWidth, $lineThickness);
    }
}

/**
 * Render star delimiter
 */
function render_star_delimiter()
{
    $html = '<div class="editor-delimiter editor-delimiter-star">';
    $html .= '<div class="delimiter-content">';
    $html .= '<div class="star-container">';
    $html .= '<span class="star star-large">★</span>';
    $html .= '<span class="star star-medium">★</span>';
    $html .= '<span class="star star-large">★</span>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * Render dash delimiter
 */
function render_dash_delimiter()
{
    $html = '<div class="editor-delimiter editor-delimiter-dash">';
    $html .= '<div class="delimiter-content">';
    $html .= '<div class="dash-container">';
    for ($i = 0; $i < 5; $i++) {
        $html .= '<span class="dash"></span>';
    }
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * Render line delimiter
 */
function render_line_delimiter($lineWidth, $lineThickness)
{
    // Convert lineWidth percentage to CSS width
    $widthClass = get_width_class($lineWidth);
    $heightClass = get_height_class($lineThickness);

    $html = '<div class="editor-delimiter editor-delimiter-line">';
    $html .= '<div class="delimiter-content">';
    $html .= '<div class="line-container">';
    $html .= '<div class="line ' . $widthClass . ' ' . $heightClass . '"></div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * Get width class for line delimiter
 */
function get_width_class($width)
{
    switch ($width) {
        case 8:
            return 'width-8';
        case 15:
            return 'width-15';
        case 25:
            return 'width-25';
        case 35:
            return 'width-35';
        case 50:
            return 'width-50';
        case 60:
            return 'width-60';
        case 100:
            return 'width-100';
        default:
            return 'width-25';
    }
}

/**
 * Get height class for line delimiter
 */
function get_height_class($thickness)
{
    switch ($thickness) {
        case 1:
            return 'height-1';
        case 2:
            return 'height-2';
        case 3:
            return 'height-3';
        case 4:
            return 'height-4';
        case 5:
            return 'height-5';
        case 6:
            return 'height-6';
        default:
            return 'height-2';
    }
}

/**
 * Render embed block
 */
function render_embed_block($data)
{
    $service = $data['service'] ?? '';
    $embed = $data['embed'] ?? '';
    $width = $data['width'] ?? 580;
    $height = $data['height'] ?? 320;
    $caption = $data['caption'] ?? '';

    if (empty($embed)) {
        return '';
    }

    $html = '<figure class="editor-embed">';
    $html .= '<div class="embed-container">';
    $html .= '<iframe src="' . htmlspecialchars($embed) . '" width="' . $width . '" height="' . $height . '" frameborder="0"></iframe>';
    $html .= '</div>';

    if (!empty($caption)) {
        $html .= '<figcaption>' . htmlspecialchars($caption) . '</figcaption>';
    }

    $html .= '</figure>';

    return $html;
}

/**
 * Render columns block
 */
function render_columns_block($data)
{
    $columns = $data['cols'] ?? [];

    if (empty($columns)) {
        return '';
    }

    $columnCount = count($columns);
    $html = '<div class="editor-columns-block" style="display: grid; grid-template-columns: repeat(' . $columnCount . ', 1fr); gap: 1.75rem; padding: 1rem 0; width: 100%; max-width: 568px; margin: 0 auto;">';

    foreach ($columns as $colIndex => $col) {
        $html .= '<div class="editor-column" data-col-index="' . $colIndex . '">';

        $blocks = $col['blocks'] ?? [];
        foreach ($blocks as $blockIndex => $block) {
            $html .= render_column_block($block, $blockIndex, $colIndex);
        }

        $html .= '</div>';
    }

    $html .= '</div>';

    return $html;
}

/**
 * Render a single block within a column
 */
function render_column_block($block, $blockIndex, $colIndex)
{
    $type = $block['type'] ?? '';
    $data = $block['data'] ?? [];

    switch ($type) {
        case 'paragraph':
            return render_column_paragraph_block($data);

        case 'header':
            return render_header_block($data);

        case 'image':
            return render_column_image_block($data, $colIndex);

        default:
            return '';
    }
}

/**
 * Render paragraph block for columns (with reduced padding)
 */
function render_column_paragraph_block($data)
{
    $text = $data['text'] ?? '';
    $alignment = $data['alignment'] ?? 'left';

    return '<p style="text-align: ' . htmlspecialchars($alignment) . '; padding: 0;">' . $text . '</p>';
}

/**
 * Render image block for columns (with special styling)
 */
function render_column_image_block($data, $colIndex)
{
    $url = $data['file']['url'] ?? '';
    $caption = $data['caption'] ?? '';

    if (empty($url)) {
        return '';
    }

    $classes = ['editor-image', 'column-image'];
    $styles = ['padding: 0', 'transform: scale(1.5)'];

    // Add origin styling based on column index
    if ($colIndex === 0) {
        $styles[] = 'transform-origin: top right';
    } elseif ($colIndex === 1) {
        $styles[] = 'transform-origin: top left';
    }

    $html = '<figure class="' . implode(' ', $classes) . '" style="' . implode('; ', $styles) . ';">';
    $html .= '<img src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($caption) . '" style="width: 100%; height: auto;">';

    if (!empty($caption)) {
        $html .= '<figcaption>' . htmlspecialchars($caption) . '</figcaption>';
    }

    $html .= '</figure>';

    return $html;
}

/**
 * Render list block
 */
function render_list_block($data)
{
    $items = $data['items'] ?? [];
    $style = $data['style'] ?? 'unordered';

    if (empty($items)) {
        return '';
    }

    $tag = $style === 'ordered' ? 'ol' : 'ul';
    $html = '<' . $tag . ' class="editor-list">';

    foreach ($items as $item) {
        $html .= '<li>' . ($item['content'] ?? $item) . '</li>';
    }

    $html .= '</' . $tag . '>';

    return $html;
}

/**
 * Render checklist block
 */
function render_checklist_block($data)
{
    $items = $data['items'] ?? [];

    if (empty($items)) {
        return '';
    }

    $html = '<div class="editor-checklist">';

    foreach ($items as $item) {
        $checked = $item['checked'] ?? false;
        $text = $item['text'] ?? '';

        $html .= '<div class="checklist-item">';
        $html .= '<input type="checkbox" ' . ($checked ? 'checked' : '') . ' disabled>';
        $html .= '<span>' . $text . '</span>';
        $html .= '</div>';
    }

    $html .= '</div>';

    return $html;
}

/**
 * Render code block
 */
function render_code_block($data)
{
    $code = $data['code'] ?? '';

    if (empty($code)) {
        return '';
    }

    $html = '<pre class="editor-code">';
    $html .= '<code>' . htmlspecialchars($code) . '</code>';
    $html .= '</pre>';

    return $html;
}

/**
 * Render table block
 */
function render_table_block($data)
{
    $content = $data['content'] ?? [];
    $withHeadings = $data['withHeadings'] ?? false;

    if (empty($content)) {
        return '';
    }

    $html = '<table class="editor-table">';

    foreach ($content as $rowIndex => $row) {
        $html .= '<tr>';

        foreach ($row as $cell) {
            $tag = ($withHeadings && $rowIndex === 0) ? 'th' : 'td';
            $html .= '<' . $tag . '>' . $cell . '</' . $tag . '>';
        }

        $html .= '</tr>';
    }

    $html .= '</table>';

    return $html;
}
