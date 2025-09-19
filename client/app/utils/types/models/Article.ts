import type {User} from "~/utils/types/models/User";
import type {Tag} from "~/utils/types/models/Tag";
import type {Category} from "~/utils/types/models/Category";
export type Article = {
    id: string|number;
    title: string;
    post: string;
    user: User;
    category: Category;
    created_at: string;
    updated_at?: string;
    deleted_at?: string;
    tags: Tag[];
    image?: string | null;
}