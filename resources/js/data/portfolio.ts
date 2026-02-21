export interface PortfolioMedia {
    id: number;
    type: 'photo' | 'video';
    url: string;
    title: string;
}

export interface PortfolioEvent {
    id: string;
    title: string;
    date: string;
    coverImage: string;
    description: string;
    media: PortfolioMedia[];
}

export const portfolioEvents: PortfolioEvent[] = [
    {
        id: 'forro-de-responsa-2025',
        title: 'Forró de Responsa 2025',
        date: '15 de Junho, 2025',
        coverImage: 'https://images.unsplash.com/photo-1470229722913-7c092fb1f144?q=80&w=2942&auto=format&fit=crop',
        description: 'A maior festa de São João da Bahia com atrações incríveis e muita animação.',
        media: [
            { id: 1, type: 'video', url: 'https://images.unsplash.com/photo-1470229722913-7c092fb1f144?q=80&w=2942&auto=format&fit=crop', title: 'AFTERMOVIE Forró de Responsa 2025' },
            { id: 2, type: 'photo', url: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2874&auto=format&fit=crop', title: 'Bastidores do Palco Principal' },
            { id: 3, type: 'photo', url: 'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?q=80&w=2940&auto=format&fit=crop', title: 'Público na Arena' },
            { id: 4, type: 'photo', url: 'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?q=80&w=2940&auto=format&fit=crop', title: 'Festa no Pôr do Sol' },
        ]
    },
    {
        id: 'reveillon-ktorze',
        title: 'Réveillon KTORZE',
        date: '31 de Dezembro, 2025',
        coverImage: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2940&auto=format&fit=crop',
        description: 'A virada de ano inesquecível com os melhores DJs e pé na areia.',
        media: [
            { id: 1, type: 'video', url: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2940&auto=format&fit=crop', title: 'Teaser Reveillon' },
            { id: 2, type: 'photo', url: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=2940&auto=format&fit=crop', title: 'Queima de fogos' },
            { id: 3, type: 'photo', url: 'https://images.unsplash.com/photo-1506157786151-b8491531f063?q=80&w=2940&auto=format&fit=crop', title: 'Festa Privada' },
            { id: 4, type: 'photo', url: 'https://images.unsplash.com/photo-1543807535-eceef0bc6599?q=80&w=2940&auto=format&fit=crop', title: 'Amanhecer' },
        ]
    },
    {
        id: 'sunset-vibes-2024',
        title: 'Sunset Vibes 2024',
        date: '12 de Novembro, 2024',
        coverImage: 'https://images.unsplash.com/photo-1533174000220-4b4cb4cb2b5b?q=80&w=2940&auto=format&fit=crop',
        description: 'Um evento ao pôr do sol com muita música eletrônica e boas energias.',
        media: [
            { id: 1, type: 'photo', url: 'https://images.unsplash.com/photo-1533174000220-4b4cb4cb2b5b?q=80&w=2940&auto=format&fit=crop', title: 'Começo do Pôr do Sol' },
            { id: 2, type: 'photo', url: 'https://images.unsplash.com/photo-1540039155732-68c8c08b541e?q=80&w=3267&auto=format&fit=crop', title: 'Palco Iluminado' },
            { id: 3, type: 'video', url: 'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?q=80&w=2940&auto=format&fit=crop', title: 'Recap do Evento' },
        ]
    }
];
