import {IconDefinition} from "@fortawesome/free-solid-svg-icons";

export interface SidebarLink {
    to: string;
    icon: IconDefinition,
    label: string;
    permissionLevel?: number;
}
