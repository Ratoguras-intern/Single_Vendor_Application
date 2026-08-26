<style>
    html { scroll-behavior: smooth; }

    .page-content { font-size: 0.9375rem; line-height: 1.8; }
    .page-content > *:first-child { margin-top: 0 !important; }

    .page-content p { margin: 0 0 1rem; }
    .page-content > p:first-of-type {
        font-size: 1.0625rem;
        line-height: 1.75;
        color: rgb(var(--color-secondary-700));
        font-weight: 450;
    }
    .dark .page-content > p:first-of-type { color: rgb(var(--color-secondary-300)); }

    .page-content h2 {
        scroll-margin-top: calc(var(--navbar-height, 80px) + 1rem);
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.35;
        letter-spacing: -0.01em;
        color: rgb(var(--color-secondary-900));
        margin: 2.5rem 0 0.875rem;
    }
    .dark .page-content h2 { color: #fff; }
    .page-content h2::before {
        content: '';
        width: 4px;
        height: 1.2em;
        border-radius: 2px;
        background: linear-gradient(to bottom, rgb(var(--color-primary-400)), rgb(var(--color-primary-600)));
        flex-shrink: 0;
    }

    .page-content h3 {
        scroll-margin-top: calc(var(--navbar-height, 80px) + 1rem);
        font-size: 1.0625rem;
        font-weight: 600;
        color: rgb(var(--color-secondary-900));
        margin: 1.75rem 0 0.5rem;
        line-height: 1.4;
    }
    .dark .page-content h3 { color: #fff; }

    .page-content h4 {
        font-size: 0.9375rem;
        font-weight: 600;
        color: rgb(var(--color-secondary-900));
        margin: 1.5rem 0 0.375rem;
    }
    .dark .page-content h4 { color: #fff; }

    .page-content ul {
        list-style: none;
        padding-left: 0;
        margin: 0 0 1rem;
    }
    .page-content ul > li {
        position: relative;
        padding-left: 1.375rem;
        margin-bottom: 0.45rem;
        line-height: 1.7;
    }
    .page-content ul > li::before {
        content: '';
        position: absolute;
        left: 0.1875rem;
        top: 0.65em;
        width: 6px;
        height: 6px;
        border-radius: 9999px;
        background: rgb(var(--color-primary-500));
    }

    .page-content ol {
        list-style: decimal;
        padding-left: 1.5rem;
        margin: 0 0 1rem;
    }
    .page-content ol > li { margin-bottom: 0.45rem; line-height: 1.7; }
    .page-content ol > li::marker {
        color: rgb(var(--color-primary-500));
        font-weight: 600;
    }

    .page-content li > ul,
    .page-content li > ol { margin-top: 0.45rem; margin-bottom: 0; }

    .page-content strong {
        font-weight: 600;
        color: rgb(var(--color-secondary-900));
    }
    .dark .page-content strong { color: #fff; }

    .page-content a {
        color: rgb(var(--color-primary-600));
        font-weight: 500;
        text-decoration: underline;
        text-decoration-color: rgb(var(--color-primary-300));
        text-decoration-thickness: 1px;
        text-underline-offset: 3px;
        transition: color 0.15s ease, text-decoration-color 0.15s ease;
    }
    .dark .page-content a { color: rgb(var(--color-primary-400)); text-decoration-color: rgba(232, 155, 45, 0.4); }
    .page-content a:hover {
        color: rgb(var(--color-primary-700));
        text-decoration-color: currentColor;
    }
    .dark .page-content a:hover { color: rgb(var(--color-primary-300)); }

    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
        margin: 1.25rem 0;
    }

    .page-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid rgb(var(--color-secondary-200));
        display: block;
        overflow-x: auto;
    }
    .dark .page-content table { border-color: rgb(var(--color-secondary-700)); }

    .page-content table th,
    .page-content table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgb(var(--color-secondary-200));
    }
    .dark .page-content table th,
    .dark .page-content table td {
        border-bottom-color: rgb(var(--color-secondary-700));
    }

    .page-content table th {
        font-weight: 600;
        color: rgb(var(--color-secondary-900));
        background: rgb(var(--color-secondary-50));
        font-size: 0.8125rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .dark .page-content table th {
        color: #fff;
        background: rgb(var(--color-secondary-800));
    }

    .page-content table tr:last-child td { border-bottom: 0; }

    .page-content table tr:hover td { background: rgb(var(--color-secondary-50)); }
    .dark .page-content table tr:hover td { background: rgba(255,255,255,0.02); }

    .page-content blockquote {
        border-left: 4px solid rgb(var(--color-primary-500));
        padding: 0.875rem 1.25rem;
        margin: 1.5rem 0;
        background: rgb(var(--color-primary-50));
        border-radius: 0.5rem 0.75rem 0.75rem 0.5rem;
        font-style: italic;
        color: rgb(var(--color-secondary-700));
    }
    .dark .page-content blockquote {
        background: rgba(232, 155, 45, 0.06);
        color: rgb(var(--color-secondary-300));
    }

    .page-content hr {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, transparent, rgb(var(--color-secondary-200)), transparent);
        margin: 2rem 0;
    }
    .dark .page-content hr { background: linear-gradient(to right, transparent, rgb(var(--color-secondary-700)), transparent); }

    .page-content code {
        font-size: 0.875em;
        background: rgb(var(--color-secondary-100));
        padding: 0.15rem 0.4rem;
        border-radius: 0.25rem;
        font-family: ui-monospace, monospace;
        color: rgb(var(--color-secondary-800));
    }
    .dark .page-content code { background: rgba(255,255,255,0.08); color: rgb(var(--color-secondary-200)); }

    .page-content pre {
        background: rgb(var(--color-secondary-900));
        color: rgb(var(--color-secondary-100));
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .page-content pre code {
        background: none;
        padding: 0;
        color: inherit;
    }

    .page-content em { font-style: italic; }
</style>
