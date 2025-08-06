# PR9 Training System Theme Customization

This document explains how to customize the theme colors and styling for the PR9 Training System.

## 🎨 Theme File Location

The main theme file is located at: `public/css/theme.css`

## 🎯 Color Customization

### Primary Colors
To change the main brand colors, modify these CSS custom properties in `public/css/theme.css`:

```css
:root {
    /* Primary Colors */
    --primary-color: #3b82f6;      /* Main brand color */
    --primary-dark: #1e40af;       /* Darker shade for hover states */
    --primary-light: rgba(59, 130, 246, 0.1); /* Light shade for backgrounds */
}
```

### Background Colors
```css
:root {
    /* Background Colors */
    --background-primary: #ffffff;     /* Main background */
    --background-secondary: #f8fafc;   /* Secondary background */
    --background-tertiary: #f1f5f9;    /* Tertiary background */
    --background-gradient: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 100%);
}
```

### Text Colors
```css
:root {
    /* Text Colors */
    --text-primary: #1e293b;      /* Main text color */
    --text-secondary: #475569;    /* Secondary text color */
    --text-muted: #64748b;        /* Muted text color */
}
```

### Status Colors
```css
:root {
    /* Status Colors */
    --success-color: #10b981;     /* Success/green */
    --warning-color: #f59e0b;     /* Warning/orange */
    --danger-color: #dc2626;      /* Danger/red */
    --danger-dark: #b91c1c;       /* Darker danger color */
}
```

## 🌈 Pre-built Theme Variations

The theme file includes several pre-built color variations that you can apply by adding a class to the `<body>` element:

### Blue Theme (Default)
```html
<body class="prompt">
```

### Green Theme
```html
<body class="prompt theme-green">
```

### Purple Theme
```html
<body class="prompt theme-purple">
```

### Dark Theme
```html
<body class="prompt theme-dark">
```

## 🔧 Custom Theme Creation

To create a custom theme, add a new CSS class in `public/css/theme.css`:

```css
/* Custom Theme Example */
.theme-custom {
    --primary-color: #your-color;
    --primary-dark: #your-dark-color;
    --primary-light: rgba(your-color, 0.1);
    
    --background-primary: #your-bg-color;
    --background-secondary: #your-secondary-bg;
    --background-gradient: linear-gradient(135deg, #color1 0%, #color2 100%);
    
    --text-primary: #your-text-color;
    --text-secondary: #your-secondary-text;
    --text-muted: #your-muted-text;
}
```

Then apply it to your layout:
```html
<body class="prompt theme-custom">
```

## 📱 Responsive Design

The theme includes responsive breakpoints:
- **Desktop**: 1024px and above
- **Tablet**: 768px to 1023px
- **Mobile**: 480px to 767px
- **Small Mobile**: Below 480px

## 🎭 Component Styling

### Navigation Bar
- Uses `--background-primary` and `--background-secondary` for gradient
- Uses `--text-secondary` for navigation links
- Uses `--primary-color` for active/hover states

### Buttons
- Primary buttons use `--primary-color`
- Secondary buttons use `--secondary-color`
- Danger buttons use `--danger-color`

### Cards and Containers
- Use `--background-primary` for main containers
- Use `--border-color` for borders
- Use `--shadow-light`, `--shadow-medium`, `--shadow-heavy` for shadows

## 🚀 Quick Color Changes

### Change to Blue Theme
```css
:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-light: rgba(37, 99, 235, 0.1);
}
```

### Change to Green Theme
```css
:root {
    --primary-color: #059669;
    --primary-dark: #047857;
    --primary-light: rgba(5, 150, 105, 0.1);
}
```

### Change to Purple Theme
```css
:root {
    --primary-color: #7c3aed;
    --primary-dark: #6d28d9;
    --primary-light: rgba(124, 58, 237, 0.1);
}
```

## 📝 Best Practices

1. **Always use CSS custom properties** instead of hardcoded colors
2. **Test color contrast** for accessibility
3. **Keep color variations consistent** across the application
4. **Use semantic color names** (primary, secondary, danger, etc.)
5. **Test on different screen sizes** to ensure readability

## 🔍 Testing Your Changes

After making color changes:
1. Clear your browser cache
2. Test on different devices and screen sizes
3. Verify accessibility with color contrast tools
4. Check that all interactive elements are clearly visible

## 📚 Additional Resources

- [CSS Custom Properties Guide](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)
- [Color Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [Color Palette Generator](https://coolors.co/) 